<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ballot;
use App\Models\ObjResponse;
use App\Models\Participation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function dashboard(Request $request)
    {
        // 1. Totales generales
        $totalProjects = Project::count();
        $totalParticipations = Participation::count();
        $totalBallots = Ballot::count();
        $totalCasillasActivas = DB::table('vw_users')
            ->where('role_id', 3)
            ->where('casilla_active', 1)
            ->count();

        // 2. Participaciones por tipo de documento
        $participationsByType = Participation::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // 3. Boletas por casilla (usando vw_users)
        $ballotsByCasilla = DB::table('vw_users as u')
            ->join('ballots as b', 'u.id', '=', 'b.user_id')
            ->select('u.casilla_place', DB::raw('count(*) as total'))
            ->groupBy('u.casilla_place')
            ->orderBy('total', 'desc')
            ->get();

        // 4. Boletas por distrito (usando casilla_district de vw_users)
        $ballotsByDistrict = DB::table('vw_users as u')
            ->join('ballots as b', 'u.id', '=', 'b.user_id')
            ->select(DB::raw("COALESCE(CAST(u.casilla_district AS CHAR), 'Casilla Especial') as casilla_district"), DB::raw('count(*) as total'))
            ->groupBy('casilla_district')
            ->orderBy('casilla_district')
            ->get();

        // 5. Top 10 proyectos más votados (general)
        $topProjects = $this->getTopProjects(10);

        // 6. Participaciones por hora (últimas 24 horas)
        $participationsByHour = $this->getParticipationsByHour();

        // 7. Votos por distrito (desde proyectos votados)
        $votesByDistrict = $this->getVotesByDistrict();

        // 8. (NUEVO) Top 10 proyectos más votados por distrito
        $topProjectsByDistrict = $this->getTopProjectsByDistrict(10);

        // 9. (NUEVO) Conteo de votos nulos (generales y por distrito)
        $nullVotesStats = $this->getNullVotesStats();

        // 10. Participaciones por casilla (número de ciudadanos que registraron su participación)
        $participationsByCasilla = DB::table('vw_users as u')
            ->join('participations as p', 'u.id', '=', 'p.user_id')
            ->select('u.casilla_place', 'u.casilla_type', DB::raw('count(*) as total'))
            ->groupBy('u.casilla_place', 'u.casilla_type')
            ->orderBy('total', 'desc')
            ->get();

        // 11. Total de votos válidos (omitir nulos/ceros)
        $totalVotes = $this->getTotalVotes();

        // 12. Votos de proyectos por casilla (para listado y gráficas)
        $votesByCasilla = $this->getVotesByCasilla();

        $data = [
            'status' => true,
            'data' => [
                'totals' => [
                    'projects' => $totalProjects,
                    'participations' => $totalParticipations,
                    'ballots' => $totalBallots,
                    'active_casillas' => $totalCasillasActivas,
                    'null_votes' => $nullVotesStats['total_null_votes'], // añadido
                    'total_votes' => $totalVotes, // nuevo
                ],
                'participations_by_type' => $participationsByType,
                'participations_by_casilla' => $participationsByCasilla,
                'ballots_by_casilla' => $ballotsByCasilla,
                'ballots_by_district' => $ballotsByDistrict,
                'top_projects' => $topProjects,
                'participations_by_hour' => $participationsByHour,
                'votes_by_district' => $votesByDistrict,
                'top_projects_by_district' => $topProjectsByDistrict,      // nuevo
                'null_votes_by_district' => $nullVotesStats['by_district'], // nuevo
                'votes_by_casilla' => $votesByCasilla, // nuevo
            ]
        ];

        return ObjResponse::success($data);
    }

    /**
     * Obtiene el top N de proyectos más votados (general).
     */
    private function getTopProjects(int $limit = 10): array
    {
        return DB::select("
            SELECT p.id, CONCAT(p.folio, ' - ',p.project_name,' - ',p.project_place) as project_name, p.assigned_district, COUNT(*) as votos
            FROM (
                SELECT vote_1 as project_id FROM ballots
                UNION ALL
                SELECT vote_2 FROM ballots
                UNION ALL
                SELECT vote_3 FROM ballots
                UNION ALL
                SELECT vote_4 FROM ballots
                UNION ALL
                SELECT vote_5 FROM ballots
            ) AS votes
            JOIN projects p ON p.id = votes.project_id
            WHERE votes.project_id IS NOT NULL AND votes.project_id > 0
            GROUP BY p.id, p.project_name, p.assigned_district
            ORDER BY votos DESC");
        //     LIMIT ?
        // ", [$limit]);
    }

    /**
     * Obtiene los votos acumulados por distrito (desde proyectos votados).
     */
    private function getVotesByDistrict(): array
    {
        return DB::select("
            SELECT p.assigned_district, COUNT(*) as votos
            FROM (
                SELECT vote_1 as project_id FROM ballots
                UNION ALL SELECT vote_2 FROM ballots
                UNION ALL SELECT vote_3 FROM ballots
                UNION ALL SELECT vote_4 FROM ballots
                UNION ALL SELECT vote_5 FROM ballots
            ) AS votes
            JOIN projects p ON p.id = votes.project_id
            WHERE votes.project_id IS NOT NULL AND votes.project_id > 0
            GROUP BY p.assigned_district
            ORDER BY p.assigned_district
        ");
    }

    /**
     * Participaciones agregadas por hora (últimas 24 horas).
     */
    private function getParticipationsByHour(): array
    {
        return DB::select("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as hour,
                COUNT(*) as total
            FROM participations
            WHERE created_at >= NOW() - INTERVAL 24 HOUR
            GROUP BY hour
            ORDER BY hour ASC
        ");
    }

    /**
     * (NUEVO) Top N proyectos más votados por distrito.
     * Retorna un array indexado por distrito, cada elemento con su top 10.
     */
    private function getTopProjectsByDistrict(int $limit = 3): array
    {
        $sql = "
            SELECT 
                p.assigned_district,
                p.id,
                CONCAT(p.folio, ' - ',p.project_name,' - ',p.project_place) as project_name,
                COUNT(*) as votos
            FROM (
                SELECT vote_1 as project_id FROM ballots
                UNION ALL SELECT vote_2 FROM ballots
                UNION ALL SELECT vote_3 FROM ballots
                UNION ALL SELECT vote_4 FROM ballots
                UNION ALL SELECT vote_5 FROM ballots
            ) AS votes
            JOIN projects p ON p.id = votes.project_id
            WHERE votes.project_id IS NOT NULL AND votes.project_id > 0
            GROUP BY p.assigned_district, p.id, p.project_name
            ORDER BY p.assigned_district, votos DESC
        ";

        $all = DB::select($sql);

        $result = [];
        foreach ($all as $row) {
            $district = $row->assigned_district;
            if (!isset($result[$district])) {
                $result[$district] = [];
            }
            if (count($result[$district]) < $limit) {
                $result[$district][] = [
                    'id' => $row->id,
                    'project_name' => $row->project_name,
                    'votos' => $row->votos,
                ];
            }
        }
        return $result;
    }

    /**
     * Estadísticas de votos nulos.
     * Retorna:
     * - total_null_votes: cantidad total de campos vote_* que son NULL o 0.
     * - by_district: array con la cantidad de votos nulos por distrito (usando la casilla del votante).
     */
    private function getNullVotesStats(): array
    {
        // Total de votos nulos (en todas las boletas)
        $totalNull = DB::selectOne("
            SELECT 
                SUM(
                    (CASE WHEN vote_1 IS NULL OR vote_1 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN vote_2 IS NULL OR vote_2 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN vote_3 IS NULL OR vote_3 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN vote_4 IS NULL OR vote_4 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN vote_5 IS NULL OR vote_5 = 0 THEN 1 ELSE 0 END)
                ) as total_nulos
            FROM ballots
            WHERE deleted_at IS NULL
        ");

        // Votos nulos por distrito (usando la casilla del votante desde vw_users)
        $byDistrict = DB::select("
            SELECT 
                COALESCE(CAST(u.casilla_district AS CHAR), 'Casilla Especial') as district,
                SUM(
                    (CASE WHEN b.vote_1 IS NULL OR b.vote_1 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN b.vote_2 IS NULL OR b.vote_2 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN b.vote_3 IS NULL OR b.vote_3 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN b.vote_4 IS NULL OR b.vote_4 = 0 THEN 1 ELSE 0 END) +
                    (CASE WHEN b.vote_5 IS NULL OR b.vote_5 = 0 THEN 1 ELSE 0 END)
                ) as nulos
            FROM ballots b
            JOIN vw_users u ON u.id = b.user_id
            WHERE b.deleted_at IS NULL
            GROUP BY district
            ORDER BY district
        ");

        return [
            'total_null_votes' => (int) ($totalNull->total_nulos ?? 0),
            'by_district' => $byDistrict,
        ];
    }

    /**
     * Total de votos válidos (suma de todos los campos vote_* que no son NULL ni 0)
     */
    private function getTotalVotes(): int
    {
        $result = DB::selectOne("
            SELECT SUM(
                (CASE WHEN vote_1 IS NOT NULL AND vote_1 > 0 THEN 1 ELSE 0 END) +
                (CASE WHEN vote_2 IS NOT NULL AND vote_2 > 0 THEN 1 ELSE 0 END) +
                (CASE WHEN vote_3 IS NOT NULL AND vote_3 > 0 THEN 1 ELSE 0 END) +
                (CASE WHEN vote_4 IS NOT NULL AND vote_4 > 0 THEN 1 ELSE 0 END) +
                (CASE WHEN vote_5 IS NOT NULL AND vote_5 > 0 THEN 1 ELSE 0 END)
            ) as total
            FROM ballots
            WHERE deleted_at IS NULL
        ");
        return (int) ($result->total ?? 0);
    }

    /**
     * Votos de proyectos por casilla.
     * Retorna un array con: casilla_id, casilla_place, project_id, project_name, votes
     */
    private function getVotesByCasilla(): array
    {
        return DB::select("
            SELECT 
                u.id as casilla_id,
                p.folio,
                u.casilla_place,
                p.assigned_district,
                p.id,
                CONCAT(p.project_name,' - ',p.project_place) as project_name,
                COUNT(*) as votes
            FROM (
                SELECT user_id, vote_1 as id FROM ballots
                UNION ALL
                SELECT user_id, vote_2 FROM ballots
                UNION ALL
                SELECT user_id, vote_3 FROM ballots
                UNION ALL
                SELECT user_id, vote_4 FROM ballots
                UNION ALL
                SELECT user_id, vote_5 FROM ballots
            ) AS votes
            JOIN vw_users u ON u.id = votes.user_id
            JOIN projects p ON p.id = votes.id
            WHERE votes.id IS NOT NULL AND votes.id > 0
            GROUP BY u.id, u.casilla_place, p.id, p.project_name
            ORDER BY u.casilla_place, votes DESC
        ");
    }
}

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
            ->select('u.casilla_district', DB::raw('count(*) as total'))
            ->groupBy('u.casilla_district')
            ->orderBy('u.casilla_district')
            ->get();

        // 5. Top 10 proyectos más votados
        $topProjects = $this->getTopProjects(10);

        // 6. Participaciones por hora (últimas 24 horas)
        $participationsByHour = $this->getParticipationsByHour();

        // 7. Votos por distrito (desde proyectos votados)
        $votesByDistrict = $this->getVotesByDistrict();

        $data = [
            'totals' => [
                'projects' => $totalProjects,
                'participations' => $totalParticipations,
                'ballots' => $totalBallots,
                'active_casillas' => $totalCasillasActivas,
            ],
            'participations_by_type' => $participationsByType,
            'ballots_by_casilla' => $ballotsByCasilla,
            'ballots_by_district' => $ballotsByDistrict,
            'top_projects' => $topProjects,
            'participations_by_hour' => $participationsByHour,
            'votes_by_district' => $votesByDistrict,
        ];
        return ObjResponse::success($data);
    }

    /**
     * Obtiene el top N de proyectos más votados.
     */
    private function getTopProjects(int $limit = 10): array
    {
        return DB::select("
            SELECT p.id, CONCAT(folio,' - ',p.project_name) project_name, p.assigned_district, COUNT(*) as votos
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
            ORDER BY votos DESC
            LIMIT ?
        ", [$limit]);
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
     * Obtiene participaciones agregadas por hora (últimas 24 horas).
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

    // StatisticsController
    public function publicResults()
    {
        // Datos simplificados y anonimizados
        $topProjects = $this->getTopProjects(10);
        $votesByDistrict = $this->getVotesByDistrict();
        $totalBallots = Ballot::count();

        $data = [
            'total_votes' => $totalBallots,
            'top_projects' => $topProjects,
            'votes_by_district' => $votesByDistrict,
        ];
        return ObjResponse::success($data);
    }
}

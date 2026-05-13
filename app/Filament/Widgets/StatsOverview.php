<?php

namespace App\Filament\Widgets;

use App\Models\LeadsLog;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\Team;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $leadsThisMonth = LeadsLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $leadsLastMonth = LeadsLog::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $leadsTrend = $leadsThisMonth >= $leadsLastMonth ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $leadsColor = $leadsThisMonth >= $leadsLastMonth ? 'success' : 'danger';

        return [
            Stat::make('Total Proyek', Project::count())
                ->description(Project::where('status', 'completed')->count() . ' proyek selesai')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary')
                ->chart([
                    Project::whereMonth('created_at', now()->subMonths(6)->month)->count(),
                    Project::whereMonth('created_at', now()->subMonths(5)->month)->count(),
                    Project::whereMonth('created_at', now()->subMonths(4)->month)->count(),
                    Project::whereMonth('created_at', now()->subMonths(3)->month)->count(),
                    Project::whereMonth('created_at', now()->subMonths(2)->month)->count(),
                    Project::whereMonth('created_at', now()->subMonth()->month)->count(),
                    Project::whereMonth('created_at', now()->month)->count(),
                ]),

            Stat::make('Leads Bulan Ini', $leadsThisMonth)
                ->description('Bulan lalu: ' . $leadsLastMonth)
                ->descriptionIcon($leadsTrend)
                ->color($leadsColor)
                ->chart([
                    LeadsLog::whereMonth('created_at', now()->subMonths(6)->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->subMonths(5)->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->subMonths(4)->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->subMonths(3)->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->subMonths(2)->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->subMonth()->month)->count(),
                    LeadsLog::whereMonth('created_at', now()->month)->count(),
                ]),

            Stat::make('Total Artikel', BlogPost::count())
                ->description(BlogPost::where('is_published', true)->count() . ' artikel published')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('warning'),

            Stat::make('Testimoni Aktif', Testimonial::where('is_active', true)->count())
                ->description('dari ' . Testimonial::count() . ' total testimoni')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success'),

            Stat::make('Anggota Tim', Team::where('is_active', true)->count())
                ->description('anggota tim aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Leads', LeadsLog::count())
                ->description('sejak pertama kali digunakan')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('primary'),
        ];
    }
}
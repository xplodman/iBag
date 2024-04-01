<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $result[] = Stat::make('Total Users Registered', User::count())
                        ->icon('heroicon-m-users')
                        ->description('The total number of users that registered into the system.');
        $result[] = Stat::make('Total Articles Created', Article::count())
                        ->icon('heroicon-m-book-open')
                        ->description('The total number of articles that created into the system.');

        return $result;
    }
}

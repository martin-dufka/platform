<?php

namespace Orchid\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Foundation\Kernel\Dashboard;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        $this->registerMenu($dashboard);
    }

    protected function registerMenu(Dashboard $dashboard = null)
    {
        $panelMenu = [
            'slug' => 'Dashboard',
            'icon' => 'icon-speedometer',
            'route' => route('dashboard.index'),
            'label' => trans('dashboard::menu.Dashboard'),
            'main' => true,
            'active' => 'dashboard.index',
            'permission' => 'dashboard.index',
        ];
        $postMenu = [
            'slug' => 'Posts',
            'icon' => 'icon-note',
            'route' => '#',
            'label' => trans('dashboard::menu.Posts'),
            'childs' => true,
            'main' => true,
            'active' => 'dashboard.posts.*',
            'permission' => 'dashboard.posts',
        ];
        $toolsMenu = [
            'slug' => 'Tools',
            'icon' => 'icon-wrench',
            'route' => '#',
            'label' => trans('dashboard::menu.Tools'),
            'childs' => true,
            'main' => true,
            'active' => 'dashboard.tools.*',
            'permission' => 'dashboard.tools',
        ];
        $systemsMenu = [
            'slug' => 'Systems',
            'icon' => 'icon-organization',
            'route' => '#',
            'label' => trans('dashboard::menu.Systems'),
            'childs' => true,
            'main' => true,
            'active' => 'dashboard.systems.*',
            'permission' => 'dashboard.systems',
        ];

        $dashboard->menu->add('Main', 'dashboard::partials.leftMainMenu', $panelMenu, 1);
        $dashboard->menu->add('Main', 'dashboard::partials.leftMainMenu', $postMenu, 100);
        $dashboard->menu->add('Main', 'dashboard::partials.leftMainMenu', $toolsMenu, 500);
        $dashboard->menu->add('Main', 'dashboard::partials.leftMainMenu', $systemsMenu, 1000);

        $settingsMenu = [
            'slug' => 'settings',
            'icon' => 'fa fa-cog',
            'route' => route('dashboard.systems.settings'),
            'label' => trans('dashboard::menu.Constants'),
            'groupname' => trans('dashboard::menu.General settings'),
            'childs' => false,
            'divider' => false,
        ];

        $errorMenu = [
            'slug' => 'logs',
            'icon' => 'fa fa-bug',
            'route' => route('dashboard.index'),
            'label' => trans('dashboard::menu.Logs'),
            'groupname' => trans('dashboard::menu.Errors'),
            'childs' => false,
            'divider' => true,
        ];

        $seoMenu = [
            'slug' => 'static-pages',
            'icon' => 'icon-book-open',
            'route' => route('dashboard.index'),
            'label' => trans('dashboard::menu.Static pages'),
            'groupname' => trans('dashboard::menu.Search Engine Optimization'),
            'childs' => false,
            'divider' => false,
        ];

        $siteMapMenu = [
            'slug' => 'site-map',
            'icon' => 'icon-map',
            'route' => route('dashboard.index'),
            'label' => trans('dashboard::menu.Site Map'),
            'childs' => false,
            'divider' => true,
        ];

        $sectionMenu = [
            'slug' => 'section',
            'icon' => 'icon-briefcase',
            'route' => route('dashboard.tools.section'),
            'label' => trans('dashboard::menu.Sections'),
            'childs' => false,
            'divider' => true,
        ];

        $menuMenu = [
            'slug' => 'menu',
            'icon' => 'icon-menu',
            'route' => route('dashboard.index'),
            'label' => trans('dashboard::menu.Menu'),
            'groupname' => trans('dashboard::menu.Posts Managements'),
            'childs' => false,
            'divider' => false,
        ];

        $usersMenu = [
            'slug' => 'users',
            'icon' => 'icon-user',
            'route' => route('dashboard.systems.users'),
            'label' => trans('dashboard::menu.Users'),
            'groupname' => trans('dashboard::menu.Users'),
            'childs' => false,
            'divider' => false,
        ];

        $groupsMenu = [
            'slug' => 'groups',
            'icon' => 'fa fa-lock',
            'route' => route('dashboard.systems.roles'),
            'label' => trans('dashboard::menu.Groups'),
            'childs' => false,
            'divider' => true,
        ];

        $allPost = $dashboard->types();
        $pages = $allPost['pages'];
        foreach ($pages as $key => $page) {
            $postObject = [
                'slug' => $page->slug,
                'icon' => $page->icon,
                'route' => route('dashboard.posts.type', [$page->slug]),
                'label' => $page->name,
                'childs' => false,
            ];

            if (reset($pages) == $page) {
                $postObject['groupname'] = 'Страницы!';
            } elseif (end($pages) == $page) {
                $postObject['divider'] = true;
            }

            $dashboard->menu->add('Posts', 'dashboard::partials.leftMenu', $postObject, 1);
        }

        $blocks = $allPost['blocks'];
        foreach ($blocks as $key => $block) {
            $blockObject = [
                'slug' => $block->slug,
                'icon' => $block->icon,
                'route' => route('dashboard.posts.type', [$block->slug]),
                'label' => $block->name,
            ];

            if (reset($blocks) == $block) {
                $blockObject['groupname'] = 'Блоки!';
            } elseif (end($blocks) == $block) {
                $blockObject['divider'] = true;
            }

            $dashboard->menu->add('Posts', 'dashboard::partials.leftMenu', $blockObject, 1);
        }

        $dashboard->menu->add('Tools', 'dashboard::partials.leftMenu', $menuMenu, 1);
        $dashboard->menu->add('Tools', 'dashboard::partials.leftMenu', $sectionMenu, 3);

        $dashboard->menu->add('Tools', 'dashboard::partials.leftMenu', $seoMenu, 10);
        $dashboard->menu->add('Tools', 'dashboard::partials.leftMenu', $siteMapMenu, 30);

        $dashboard->menu->add('Systems', 'dashboard::partials.leftMenu', $errorMenu, 500);
        $dashboard->menu->add('Systems', 'dashboard::partials.leftMenu', $settingsMenu, 1);

        $dashboard->menu->add('Systems', 'dashboard::partials.leftMenu', $usersMenu, 501);
        $dashboard->menu->add('Systems', 'dashboard::partials.leftMenu', $groupsMenu, 601);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

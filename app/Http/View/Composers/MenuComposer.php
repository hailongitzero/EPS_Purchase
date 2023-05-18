<?php

namespace App\Http\View\Composers;

use App\Main\NavbarData;
use Illuminate\View\View;
use App\Main\TopMenu;
use App\Main\SideMenu;
use App\Main\SimpleMenu;
use App\Main\Users;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if ( request()->route() ){
            $pageName = request()->route()->getName();
            $activeMenu = $this->activeMenu($pageName, 'side-menu');

            $view->with('top_menu', TopMenu::menu());
            $view->with('side_menu', SideMenu::menu());
            $view->with('simple_menu', SimpleMenu::menu());
            $view->with('first_level_active_index', $activeMenu['first_level_active_index']);
            $view->with('second_level_active_index', $activeMenu['second_level_active_index']);
            $view->with('third_level_active_index', $activeMenu['third_level_active_index']);
            $view->with('breadcrumb', $activeMenu['breadcrumb']);
            $view->with('page_name', $pageName);
            $view->with('userInfo', Users::currentUser());
            $view->with('notifications', NavbarData::notifications());
        }
    }

    /**
     * Specify used layout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function layout($view)
    {
        if (isset($view->layout)) {
            return $view->layout;
        } else if (request()->has('layout')) {
            return request()->query('layout');
        }

        return 'side-menu';
    }

    /**
     * Determine active menu & submenu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activeMenu($pageName, $layout)
    {
        $firstLevelActiveIndex = '';
        $secondLevelActiveIndex = '';
        $thirdLevelActiveIndex = '';
        $firstLevelActivePageName = '';
        $secondLevelActivePageName = '';
        $thirdLevelActivePageName = '';
        $firstLevelActiveRouteName = '';
        $secondLevelActiveRouteName = '';
        $thirdLevelActiveRouteName = '';
        

        if ($layout == 'top-menu') {
            foreach (TopMenu::menu() as $menuKey => $menu) {
                if (isset($menu['route_name']) && $menu['route_name'] == $pageName && empty($firstPageName)) {
                    $firstLevelActiveIndex = $menuKey;
                    $firstLevelActivePageName = $menu['title'];
                    $firstLevelActiveRouteName = $menu['route_name'];
                }

                if (isset($menu['sub_menu'])) {
                    foreach ($menu['sub_menu'] as $subMenuKey => $subMenu) {
                        if (isset($subMenu['route_name']) && $subMenu['route_name'] == $pageName && $menuKey != 'menu-layout' && empty($secondPageName)) {
                            $firstLevelActiveIndex = $menuKey;
                            $secondLevelActiveIndex = $subMenuKey;
                            $firstLevelActivePageName = $menu['title'];
                            $secondLevelActivePageName = $subMenu['title'];
                            $firstLevelActiveRouteName = $menu['route_name'];
                            $secondLevelActiveRouteName = $subMenu['route_name'];
                        }

                        if (isset($subMenu['sub_menu'])) {
                            foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu) {
                                if (isset($lastSubMenu['route_name']) && $lastSubMenu['route_name'] == $pageName) {
                                    $firstLevelActiveIndex = $menuKey;
                                    $secondLevelActiveIndex = $subMenuKey;
                                    $thirdLevelActiveIndex = $lastSubMenuKey;
                                    $firstLevelActivePageName = $menu['title'];
                                    $secondLevelActivePageName = $subMenu['title'];
                                    $thirdLevelActivePageName = $lastSubMenu['title'];
                                    $firstLevelActiveRouteName = $menu['route_name'];
                                    $secondLevelActiveRouteName = $subMenu['route_name'];
                                    $thirdLevelActiveRouteName = $lastSubMenu['route_name'];
                                }       
                            }
                        }
                    }
                }
            }
        } else if ($layout == 'simple-menu') {
            foreach (SimpleMenu::menu() as $menuKey => $menu) {
                if ($menu !== 'devider' && isset($menu['route_name']) && $menu['route_name'] == $pageName && empty($firstPageName)) {
                    $firstLevelActiveIndex = $menuKey;
                    $firstLevelActivePageName = $menu['title'];
                    $firstLevelActiveRouteName = $menu['route_name'];
                }

                if (isset($menu['sub_menu'])) {
                    foreach ($menu['sub_menu'] as $subMenuKey => $subMenu) {
                        if (isset($subMenu['route_name']) && $subMenu['route_name'] == $pageName && $menuKey != 'menu-layout' && empty($secondPageName)) {
                            $firstLevelActiveIndex = $menuKey;
                            $secondLevelActiveIndex = $subMenuKey;
                            $firstLevelActivePageName = $menu['title'];
                            $secondLevelActivePageName = $subMenu['title'];
                            $secondLevelActiveRouteName = $subMenu['route_name'];
                        }

                        if (isset($subMenu['sub_menu'])) {
                            foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu) {
                                if (isset($lastSubMenu['route_name']) && $lastSubMenu['route_name'] == $pageName) {
                                    $firstLevelActiveIndex = $menuKey;
                                    $secondLevelActiveIndex = $subMenuKey;
                                    $thirdLevelActiveIndex = $lastSubMenuKey;
                                    $firstLevelActivePageName = $menu['title'];
                                    $secondLevelActivePageName = $subMenu['title'];
                                    $thirdLevelActivePageName = $lastSubMenu['title'];
                                    $thirdLevelActiveRouteName = $lastSubMenu['route_name'];
                                }       
                            }
                        }
                    }
                }
            }
        } else {
            foreach (SideMenu::menu() as $menuKey => $menu) {
                if ($menu !== 'devider' && isset($menu['route_name']) && $menu['route_name'] == $pageName && empty($firstPageName)) {
                    $firstLevelActiveIndex = $menuKey;
                    $firstLevelActivePageName = $menu['title'];
                    $firstLevelActiveRouteName = $menu['route_name'];
                }

                if (isset($menu['sub_menu'])) {
                    foreach ($menu['sub_menu'] as $subMenuKey => $subMenu) {
                        if (isset($subMenu['route_name']) && $subMenu['route_name'] == $pageName && $menuKey != 'menu-layout' && empty($secondPageName)) {
                            $firstLevelActiveIndex = $menuKey;
                            $secondLevelActiveIndex = $subMenuKey;
                            $firstLevelActivePageName = $menu['title'];
                            $secondLevelActivePageName = $subMenu['title'];
                            $secondLevelActiveRouteName = $subMenu['route_name'];
                        }

                        if (isset($subMenu['sub_menu'])) {
                            foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu) {
                                if (isset($lastSubMenu['route_name']) && $lastSubMenu['route_name'] == $pageName) {
                                    $firstLevelActiveIndex = $menuKey;
                                    $secondLevelActiveIndex = $subMenuKey;
                                    $thirdLevelActiveIndex = $lastSubMenuKey;
                                    $firstLevelActivePageName = $menu['title'];
                                    $secondLevelActivePageName = $subMenu['title'];
                                    $thirdLevelActivePageName = $lastSubMenu['title'];
                                    $thirdLevelActiveRouteName = $lastSubMenu['route_name'];
                                }       
                            }
                        }
                    }
                }
            }
        }

        return [
            'first_level_active_index' => $firstLevelActiveIndex,
            'second_level_active_index' => $secondLevelActiveIndex,
            'third_level_active_index' => $thirdLevelActiveIndex,
            'breadcrumb' => array(
                [
                    'page_name'=> $firstLevelActivePageName,
                    'route_name' => $firstLevelActiveRouteName
                ],
                [
                    'page_name'=> $secondLevelActivePageName,
                    'route_name' => $secondLevelActiveRouteName
                ],
                [
                    'page_name'=> $thirdLevelActivePageName,
                    'route_name' => $thirdLevelActiveRouteName
                ],
            ),
        ];
    }
}
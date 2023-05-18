<?php

namespace App\Main;

class SimpleMenu
{
    /**
     * List of simple menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function menu()
    {
        return [
            'dashboard' => [
                'devider' => false,
                'icon' => 'home',
                'route_name' => 'dashboard',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Dashboard',
                'role' => 0,
            ],
            'makeRequest' => [
                'devider' => false,
                'icon' => 'edit',
                'route_name' => 'request.add.view',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Tạo yêu cầu',
                'role' => 0,
            ],
            'administrator' => [
                'devider' => false,
                'icon' => 'layers',
                'route_name' => 'administrator',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Quản lý yêu cầu',
                'role' => 2,
            ],
            'moderator' => [
                'devider' => false,
                'icon' => 'layers',
                'route_name' => 'moderator',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Quản lý yêu cầu',
                'role' => 1,
            ],
            'myRequests' => [
                'devider' => false,
                'icon' => 'layers',
                'route_name' => 'myRequests',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Yêu cầu của tôi',
                'role' => 0,
            ],
            'userManagement' => [
                'devider' => true,
                'icon' => 'user',
                'route_name' => 'userManagement',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Quản lý người dùng',
                'role' => 2,
            ],
            'file-manager' => [
                'devider' => true,
                'icon' => 'hard-drive',
                'route_name' => 'fileManage',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Hướng dẫn sử dụng',
                'role' => 0,
            ]
        ];
    }
}

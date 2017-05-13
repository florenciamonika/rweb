<?php

return array(
    'cms_name' => 'Social Reward',
    'max_lock_count' => 5,
    'cms_session_name' => array(
        'admin_id' => 'Reward'
    ),
    'admin_default_password' => 'reward',
    'menus' => array(
        'dashboard' => array(
            'label' => 'Dashboard',
            'route' => 'backend',
            'icon_class' => 'fa fa-dashboard',
            'permission' => false,
        ),
        'admin_management' => array(
            'label' => 'Admin Management',
            'icon_class' => 'fa fa-columns',
            'permission' => false,
            'submenus' => array(
                'admin_user' => array(
                    'label' => 'Admin User',
                    'route' => 'backend/admin-user',
                    'icon_class' => 'fa fa-users',
                    'permission' => true,
                ),
            )
        ),
        'homebanner' => array(
            'label' => 'Home Banner',
            'icon_class' => 'fa fa-picture-o',
            'permission' => false,
            'route' => 'backend/homebanner/',
            'permission' => true,
        ),
        'brand' => array(
            'label' => 'Brand Management',
            'icon_class' => 'fa fa-archive',
            'permission' => false,
            'submenus' => array(
                'brand_organizer' => array(
                    'label' => 'Organize',
                    'route' => 'backend/brand/',
                    'icon_class' => 'fa fa-user-plus',
                    'permission' => true,
                )
            )
        ),
        'news' => array(
            'label' => 'News Management',
            'icon_class' => 'fa fa-newspaper-o',
            'permission' => false,
            'submenus' => array(
                'news_organizer' => array(
                    'label' => 'Organize',
                    'route' => 'backend/news/',
                    'icon_class' => 'fa fa-user-plus',
                    'permission' => true,
                )
            )
        ),
        'promo' => array(
            'label' => 'Promo Management',
            'icon_class' => 'fa fa-bullhorn',
            'permission' => false,
            'submenus' => array(
                'promo_organizer' => array(
                    'label' => 'Organize',
                    'route' => 'backend/promo/',
                    'icon_class' => 'fa fa-user-plus',
                    'permission' => true,
                ),
                'task_organizer' => array(
                    'label' => 'Task List',
                    'route' => 'backend/task/',
                    'icon_class' => 'fa fa-tasks',
                    'permission' => true,
                )
            )
        ),
        'reward' => array(
            'label' => 'Reward Management',
            'icon_class' => 'fa fa-gift',
            'permission' => false,
            'submenus' => array(
                'reward_organizer' => array(
                    'label' => 'Organize',
                    'route' => 'backend/reward/',
                    'icon_class' => 'fa fa-user-plus',
                    'permission' => true,
                )
            )
        ),
        'media_uploader' => array(
            'label' => 'Media Uploader',
            'icon_class' => 'fa fa-upload',
            'permission' => false,
            'submenus' => array(
                'media_uploader_homebanner' => array(
                    'label' => 'Home Banner',
                    'route' => 'backend/media-uploader/homebanner',
                    'icon_class' => '',
                    'permission' => true,
                ),
                'media_uploader_promo' => array(
                    'label' => 'Promo Image',
                    'route' => 'backend/media-uploader/promo',
                    'icon_class' => '',
                    'permission' => true,
                ),
                'media_uploader_reward' => array(
                    'label' => 'Reward Image',
                    'route' => 'backend/media-uploader/reward',
                    'icon_class' => '',
                    'permission' => true,
                ),
                'media_uploader_news' => array(
                    'label' => 'News Image',
                    'route' => 'backend/media-uploader/news',
                    'icon_class' => '',
                    'permission' => true,
                ),
                'media_uploader_task' => array(
                    'label' => 'Task Image',
                    'route' => 'backend/media-uploader/task',
                    'icon_class' => '',
                    'permission' => true,
                ),

            ),
        ),
    ),
);

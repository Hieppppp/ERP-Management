<?php

namespace App\Helpers;

class Menu
{
    public static function isActiveMenu(array|string $menuList, string $class)
    {
        $currentPath = request()->path();
        $currentPath = preg_replace('/\//', '', $currentPath, 1);
        if (is_array($menuList)) {
            foreach ($menuList as $menuName) {
                if(strpos($currentPath, $menuName) === 0) {
                    return $class;
                }
            }
        } else {
            if ($currentPath == $menuList) {
                return $class;
            }
        }
        return '';
    }
}

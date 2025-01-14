<?php

/**
 * Open-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * OpenAdmin\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * OpenAdmin\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use OpenAdmin\Admin\Facades\Admin;
OpenAdmin\Admin\Form::forget(['editor']);

Admin::booting(function () { // Agregar ítem al menú 
    \OpenAdmin\Admin\Facades\Admin::menu()->add([ 
        'title' => 'Gestiones', 
        'icon' => 'fa-calendar', 
        'uri' => 'gestiones', 
        ]); 
    });

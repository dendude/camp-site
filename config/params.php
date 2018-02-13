<?php

return [
    'adminEmail' => 'denis.kravtsov.1986@mail.ru',
    'adminItemsPerPage' => 20,
    
    'officeItemsPerPage' => 10,
    'officeGridTableOptions' => ['class' => 'table table-hover table-condensed table-list'],

    'group_template_simple' => '<div class="col-xs-12">
                                    <div class="{class}">
                                        <div class="input-group">
                                            {input}<span class="input-group-addon">{addon}</span>
                                        </div>
                                    </div>
                                    {error}
                                 </div>',
    
    'group_template' => '<div class="col-xs-12 col-md-4 text-right">{label}</div>
                         <div class="col-xs-12 col-md-8">
                            <div class="{class}">
                                <div class="input-group">
                                    {input}<span class="input-group-addon">{addon}</span>
                                </div>
                            </div>
                            {error}
                         </div>',

    'add_template' => '<div class="col-xs-12 col-md-4 text-right">{label}</div>
                       <div class="col-xs-12 col-md-6">{input}{error}</div>
                       <div class="col-xs-12 col-md-2">
                            <div class="btn-group btn-group-justified">
                                <div class="btn-group">
                                    <button class="btn btn-default" title="Обновить список" onclick="{refresh}" type="button"><i class="fa fa-refresh"></i></button>
                                </div>
                                <div class="btn-group">
                                    <a class="btn btn-info" href="{url}" target="_blank" title="Добавить элемент"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                       </div>',
    
    'price_template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8"><div class="input-group input-group-small">{input}<span class="input-group-addon"><strong>{price}</strong></span></div>{error}</div>',
    'area_template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8"><div class="input-group input-group-small">{input}<span class="input-group-addon"><strong>M<sup>2</sup></strong></span></div>{error}</div>',
    'date_template' => '<div class="col-xs-4 text-right">{label}</div><div class="col-xs-8"><div class="input-group input-group-small">{input}<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>{error}</div>',
    
    'required_fields' => 'Заполните обязательные поля, отмеченные символом <span class="required">*</span>',
    // http://camp-centr.ru
    'sitename' => 'https://camp-centr.ru',
    'site_url' => 'https://camp-centr.ru',
    'company' => 'Camp-Centr',
    'company_name' => 'КЭМП-ЦЕНТР',
];
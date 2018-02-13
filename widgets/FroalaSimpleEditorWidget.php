<?php
namespace app\widgets;

use yii\base\Widget;

class FroalaSimpleEditorWidget extends Widget {
    
    public $model;
    public $field;
    public $params;
    public $type;
    
    const TYPE_ROW = 'row';
    const TYPE_CELL = 'cell';
    
    public function run()
    {
        $buttons = [
            'undo', 'redo' , '|',
            'bold', 'italic', 'underline', '|',
            'formatOL', 'formatUL', '|',
            'alert', 'clear'
        ];
        
        $params = [
            'toolbarButtons' => $buttons,
            'toolbarButtonsMD' => $buttons,
            'toolbarButtonsSM' => $buttons,
            'toolbarButtonsXS' => $buttons,
        ];
        
        if ($this->params) $params = array_merge($params, $this->params);
        
        if (!isset($this->type)) $this->type = self::TYPE_CELL;
        
        return $this->render('FroalaSimpleEditorWidget', [
            'model' => $this->model,
            'field' => $this->field,
            'type' => $this->type,
            'params' => $params,
        ]);
    }
}
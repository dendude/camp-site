$(document).ready(function(){
    // отправка формы
    $('form').on('beforeSubmit', function(){
        loader.show(this);
    });
});
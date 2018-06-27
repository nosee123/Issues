/**
 * Require: JQuery >= 1.0
 * Introduction: 图片预览
 * */

/*
 * obj: 传入需要预览的图片对象
 * not_move: 传入true则预览图片不随鼠标移动
 * */
function tip_start(obj, not_move) {
    var s = $(obj);
    if($("#tipdiv")[0] == null) {
        $(document.body).append("<div id=\"tipdiv\" style=\"position:absolute;left:0;top:0;display:none;\"></div>");
    }
    var t = $("#tipdiv");
    var one = false;
    s.mousemove(function(e) {
        if(not_move==true && one) return;
        var mouse = get_mousepos(e);
        t.css("left", mouse.x + 10 + 'px');
        t.css("top", mouse.y + 10 + 'px');
        t.html("<img src='" + s.attr("src")+"' />");
        t.css("display", '');
        one = true;
    });
    s.mouseout(function() {
        t.css("display", 'none');
    });
}

//获取鼠标位置
function get_mousepos(e) {
    var x, y;
    var e = e||window.event;
    return {x:e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, y:e.clientY + document.body.scrollTop + document.documentElement.scrollTop};
}
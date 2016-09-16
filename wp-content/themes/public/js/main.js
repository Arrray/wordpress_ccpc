
$(document).ready(function(){
    $(document).ready(function() {
        $(".box_skitter_large").skitter({
            animation: "random",
            interval: 3000,
            numbers: false,
            numbers_align: "right",
            hideTools: true,
            controls: false,
            focus: false,
            focus_position: true,
            width_label:'340px',
            enable_navigation_keys: true,
            progressbar: false
        });
    });
});
function showsub(li) {
    var submenu=li.getElementsByTagName("div")[0];
    submenu.style.display="block";
}
function hidesub(li) {
    var submenu=li.getElementsByTagName("div")[0];
    submenu.style.display="none";
}
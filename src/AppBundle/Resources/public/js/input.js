
if (typeof KeyEvent === "undefined")
{
    var KeyEvent = {
        DOM_VK_1: 49,
        DOM_VK_2: 50,
        DOM_VK_3: 51,
        DOM_VK_4: 52,
        DOM_VK_5: 53
    };
}

addEventListener("keydown", function (e)
{
    // Range validation
    if ( KeyEvent.DOM_VK_1 <= e.keyCode && KeyEvent.DOM_VK_5 >= e.keyCode ) {
        window.location.href = "http://localpgame.com/app_dev.php/pgame/" + (e.keyCode - KeyEvent.DOM_VK_1);
    }
});

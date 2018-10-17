var slideTime = 500;
var rootWidth = 1230;
var floatAtBottom = false;
function Ad_floating_init()
{
	xMoveTo('floating_banner_right', rootWidth - (rootWidth-screen.width), 0);
	xMoveTo('floating_banner_left', rootWidth - (screen.width), 0);
	winOnResize(); // set initial position
	xAddEventListener(window, 'resize', winOnResize, false);
	xAddEventListener(window, 'scroll', winOnScroll, true);
}
function winOnResize() {
	checkScreenWidth();
	winOnScroll(); // initial slide
}
function winOnScroll() {
	var y = xScrollTop();
		if( y<=165 ) y=0;
	if (floatAtBottom) {
		y += xClientHeight() - xHeight('floating_banner_left');
	}
	xSlideTo('floating_banner_left', (screen.width - rootWidth)/2 - (150 +15) , y, slideTime);
	xSlideTo('floating_banner_right', (screen.width + rootWidth)/2 - 2 , y, slideTime);
}
function checkScreenWidth()
{
	if( screen.width <= 1230 )
	{
		document.getElementById('floating_banner_left').style.display = 'none';
		document.getElementById('floating_banner_right').style.display = 'none';
	}
}
Ad_floating_init();
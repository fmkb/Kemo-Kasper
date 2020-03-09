try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(err) {}

var W3CDOM = (document.createElement && document.getElementsByTagName);

var mouseOvers = new Array();
var mouseOuts = new Array();

window.onload = init;

function init()
{
	if (!W3CDOM) return;
	var nav = document.getElementById('menu');
	var imgs = nav.getElementsByTagName('img');
	for (var i=0;i<imgs.length;i++)
	{
		imgs[i].onmouseover = mouseGoesOver;
		imgs[i].onmouseout = mouseGoesOut;
		var suffix = imgs[i].src.substring(imgs[i].src.lastIndexOf('.'));
		mouseOuts[i] = new Image();
		mouseOuts[i].src = imgs[i].src;
		mouseOvers[i] = new Image();
		mouseOvers[i].src = imgs[i].src.substring(0,imgs[i].src.lastIndexOf('.')) + "_down" + suffix;
		imgs[i].number = i;
	}
}

function mouseGoesOver()
{
	this.src = mouseOvers[this.number].src;
	<!-- document.getElementById("hiddenanim").style.visibility="visible"; -->
}

function mouseGoesOut()
{
	this.src = mouseOuts[this.number].src;
	<!-- document.getElementById("hiddenanim").style.visibility="visible"; -->
}

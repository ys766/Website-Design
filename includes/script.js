/*
function galleryArrangement takes the image ids as an arrary and calculate the best arrangement of the 
images when the user wants to view all images at once. 
The function galleryArrangment tries to position images in columns and tries to make each column each length
*/

var heights = [];
var widths = [];
var colWidth = 500;
var min_w = Number.MAX_SAFE_INTEGER;
var max_w = 0.0;
var min_h = Number.MAX_SAFE_INTEGER;
var max_h = 0.0;

var vertical = [];
var horizontal = [];

const IMGAGE_PATH = "/uploads/images/";

function createMidthHeight() {
	// images is an array storing all the images in the Gallery
	var images = document.getElementsByClassName("galleryImages");
	images.forEach( function (image) {
		heights.push(image.clientHeight);
		widths.push(image.clientWidth);
		if (image.clientWidth >= max_w) {
			max_w = image.clientWidth;
		}
		if (image.clientWidht <= min_w) {
			min_w = image.clientWidth;
		}
		if (image.clientHeight >= max_h) {
			max_h = image.clientHeight;
		}
		if (image.clientWidht <= min_h) {
			min_h = image.clientHeight;
		}
		if (image.clientHeight >= image.clientWidth * 1.8) {
			vertical.push(
				"width":image.clientWidth;
				"height":image.clientHeight;
				);
		}
		else {
			horizontal.push(
				"width":image.clientWidth;
				"height":image.clientHeight;
				)
		}
	});
}

	// heights and widths store the heights and the widths of the images
	// respectively. 

/*
The function arrangeImages tries to seek an optimal or suboptimal arrangements 
of the images in the gallery. 
*/
function arrangeImages(heights, widths) {
	// the window to display the images are supposed to be scrollable 
	// vertically but fixed in width according to different window sizes. 
	var window_width = document.getElementById("window").clientWidth;

	// Scale the widths and heights according to the image grid
	// set the width of all images to the same, and auto scale the height of 
	// all images. 
	heights = heights / widths * colWidth;
}
}
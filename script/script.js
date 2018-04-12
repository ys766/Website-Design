
function loadcontent () {
  Active();
}
/*
scrollActive first detects how much the container div has been scrolled. If the scroll
has exceeded a certain amount, then, obtain the sub-division and set it to
a new class to be styled.
*/
function Active () {
  var div = document.getElementById("bkg");
  div.classList.remove("fade_in");
  div.classList.add("active");
}
//     var subdivs = document.getElementsByClassName("fade_in");
//     var len = subdivs.length;
//     console.log(len);
//     // len > 0 ==> inactive div is available.
//     if (len > 0) {
//       for (var i = 0; i < len; i++) {
//         console.log(i);
//         // check if the div is visible
//         if (checkActive(subdivs[i], div) == true) {
//           console.log(checkActive(subdivs[i], div));
//           subdivs[i].classList.replace("fade_in","active");
//           console.log(subdivs[i].classList);
//         }
//       }
//     }
//     // no divs belong to class fade_in. then all divs have been shown
//     else {
//       return;
//     }
//   }
// /*
// this function checks if an inactive div is visible yet.
// */
// function checkActive(element, container) {
//   var containerTop = container.scrollTop;
//   var containerBttm = container.clientHeight + containerTop;
//
//   var elementTop = element.offsetTop;
//   var elementBttm = element.clientHeight + elementTop;
//   // scrolled too much
//   if (elementBttm <= containerTop) {
//     return true;
//   }
//   // completely visible
//   if (elementBttm <= containerBttm && elementTop >= containerTop) {
//     return true;
//   }
//   // partially visible
//   if ((elementBttm <= containerBttm && elementTop <= containerTop) ||
//   (elementBttm >= containerBttm && elementTop >= containerTop)) {
//     return true;
//   }
//   return false;
// }

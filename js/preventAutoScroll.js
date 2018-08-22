//Prevent Autoscroll for target-anchors
function bgenScroll() {
 if (window.pageYOffset!= null){
  st=window.pageYOffset+"";
 }
 if (document.body.scrollWidth!= null){
  if (document.body.scrollTop){
  st=document.body.scrollTop;
  }
  st=document.documentElement.scrollTop;
 }
  setTimeout("window.scroll(0,st)",10);
}
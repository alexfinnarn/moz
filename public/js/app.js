htmx.on("htmx:load", function(evt) {

});

htmx.on("htmx:configRequest", function(evt) {
  // Get the content within "div#styles-container" and add it to the request.
  evt.detail.headers['styles'] = document.querySelector('div#styles-container').innerHTML;
});

// HTMX Logger
htmx.logger = function(elt, event, data) {
  if(console) {
    console.log(event, elt, data);
  }
}

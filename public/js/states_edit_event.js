var $event_country = $('#event_country')
var $token = $('#event__token')
$(document).ready(function() {
  var $form = $(this).closest('form')
    var data = {}
    data[$token.attr('name')] = $token.val()
    data[$event_country.attr('name')] = $event_country.val()
    $.post($form.attr('action'), data).then(function(response)
    {
        $('#event_state').replaceWith(
            $(response).find('#event_state')
        )
    })
});
$(document).ready(function() {
  $("#event_images").fileinput({showCaption: false, dropZoneEnabled: false});
});
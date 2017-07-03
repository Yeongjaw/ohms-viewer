jQuery(function ($) {
  var loaded = false;

  

  $('#translate-link').click(function (e) {
    var urlIndexPiece = '';
    var re;
    e.preventDefault();
    if ($('#search-type').val() == 'Index') {
      var activeIndexPanel = $('#accordionHolder').accordion('option', 'active');
      if (activeIndexPanel !== false) {
        urlIndexPiece = '&index=' + activeIndexPanel;
      }
    }
    parent.widget.getPosition(function (pos) {
      if ($('#translate-link').attr('data-lang') == $('#translate-link').attr('data-linkto')) {
        re = /&translate=(.*)/g;
        location.href = location.href.replace(re, '') + '&time=' + Math.floor(pos / 1000) + '&panel=' + $('#search-type').val() + urlIndexPiece;
      } else {
        re = /&time=(.*)/g;
        location.href = location.href.replace(re, '') + '&translate=1&time=' + Math.floor(pos / 1000) + '&panel=' + $('#search-type').val() + urlIndexPiece;
      }
    });
  });

  $('body').on('click', 'a.jumpLink', function (e) {
    e.preventDefault();
    var target = $(e.target);
    curPlayPoint = 0;
    widget.getPosition(function (pos) {
      curPlayPoint = target.data('timestamp');
      widget.seekTo(curPlayPoint * 60 * 1000);
      widget.play();
    });
  });
  $('body').on('click', 'a.indexJumpLink', function (e) {
    e.preventDefault();
    var target = $(e.target);
    curPlayPoint = 0;
    widget.getPosition(function (pos) {
      curPlayPoint = target.data('timestamp');
      widget.seekTo(curPlayPoint * 1000);
      widget.play();
    });
  });

  

 
});

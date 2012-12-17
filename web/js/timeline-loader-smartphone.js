$(function(){
  var timerID;
  var timerArray = new Array();
  var timer;
  timelineAllLoad();
  if ( gorgon.timer != undefined )
  {
    timer = gorgon.timer;
  }
  else
  {
    timer = 15000;
  }
  timerID = setInterval('timelineDifferenceLoad()', timer);
 
  $('#tosaka_postform_submit').click( function() {
    setTimeout('timelineDifferenceLoad()', 1500);
  });

  $('#gorgon-loadmore').click( function() {
    $('#timeline-list-loader').show();
    $('#gorgon-loadmore').hide();
    timelineLoadmore();
    $('#timeline-list-loader').hide();
    $('#gorgon-loadmore').show();
  });

});

function timelineAllLoad() {
  if (gorgon)
  {
    gorgon.apiKey = openpne.apiKey;
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'activity/search.json',
      data: gorgon,
      success: function (json){
        renderJSON(json, 'all');
        $('#timeline-list-loader').hide();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#timeline-list-loader').hide();
        $('#timeline-list').text('タイムラインは投稿されていません。');
        $('#timeline-list').show();
      },
    });

  }
  else
  {
    $.ajax({
      type: 'GET',
      url: openpne.apiBase + 'activity/search.json?apiKey=' + openpne.apiKey,
      success: function (json){
        renderJSON(json, 'all');
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#timeline-list-loader').hide();
        $('#timeline-list').text('タイムラインは投稿されていません。');
        $('#timeline-list').show();
      },
    });
  }
}

function timelineDifferenceLoad() {
  var lastId = $('#timeline-list').attr('data-last-id');
  if (gorgon)
  {
    gorgon.apiKey = openpne.apiKey;
  }
  else
  {
    gorgon = {apiKey: openpne.apiKey,}
  }
  $.getJSON( openpne.apiBase + 'activity/search.json?count=20&since_id=' + lastId, gorgon, function(json){
    renderJSON(json, 'diff');
  });
}

function timelineLoadmore() {
  var loadmoreId = $('#timeline-list').attr('data-loadmore-id');
  loadmoreId = loadmoreId - 1;
  if (gorgon)
  {
    gorgon.apiKey = openpne.apiKey;
  }
  else
  {
    gorgon = {apiKey: openpne.apiKey,}
  }
  gorgon.max_id = loadmoreId;

  $.ajax({
    type: 'GET',
    url: openpne.apiBase + 'activity/search.json',
    data: gorgon,
    success: function(json){
      renderJSON(json, 'more');
      $('#timeline-loadmore-loading').hide();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      $('#timeline-loadmore-loading').hide();
    },  
  }); 
}

function renderJSON(json, mode) {
  if (undefined == mode)
  {
    mode = 'all';
  }
  if ('all' == mode)
  {
    $('#timeline-list').empty();
  }

  $timelineData = $('#timelineTemplate').tmpl(json.data);
  $('.timeline-comment-button', $timelineData).timelineComment();
  $('.timeline-comment-link', $timelineData).click(function(){
    $commentBoxArea = $(this).parent().siblings().find('.timeline-post-comment-form');
    $commentBoxArea.show();
    $commentBoxArea.children('.timeline-post-comment-form-input').focus();
  });
  if ('diff' == mode)
  {
    $timelineData.prependTo('#timeline-list');
  }
  else
  {
    $timelineData.appendTo('#timeline-list');
  }
  if ('all' == mode || 'diff' == mode)
  {
    if(json.data[0])
    {
      $('#timeline-list').attr('data-last-id', json.data[0].id);
    }
  }
  if ('all' == mode || 'more' == mode)
  {
    var max = json.data.length - 1;
    if (json.data[max])
    {
      $('#timeline-list').attr('data-loadmore-id', json.data[max].id);
    }
  }
  if(json.data)
  {
    for(i=0;i<json.data.length;i++)
    {
      if(json.data[i].replies)
      {
        $('#timelineCommentTemplate').tmpl(json.data[i].replies).prependTo('#commentlist-' +json.data[i].id);
        $('#timeline-post-comment-form-' + json.data[i].id, $timelineData).show();
      }
    }
  }
  if ('all' == mode)
  {
    $('#timeline-loading').hide();
  }
  if ('more' == mode)
  {
    $('#timeline-loadmore').show();
    $('#timeline-loadmore-loading').hide();
  }
}

function convertTag(str) {
  str = str.replace(/&/g,'&amp;');
  str = str.replace(/"/g,'&quot;');
  str = str.replace(/'/g,'&#039;');
  str = str.replace(/</g,'&lt;');
  str = str.replace(/>/g,'&gt;');
  return str;
}

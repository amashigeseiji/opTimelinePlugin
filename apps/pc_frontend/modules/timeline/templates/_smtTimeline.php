<?php use_helper('Javascript', 'opUtil', 'opAsset') ?>
<script type="text/javascript">
//<![CDATA[
var gorgon = {
      'mode': 'all',
      'post': {
      }
    };
//]]>
</script>
<?php op_smt_use_stylesheet('/opTimelinePlugin/css/jquery.colorbox.css') ?>
<?php op_smt_use_stylesheet('/opTimelinePlugin/css/timeline-smartphone.css', 'last') ?>
<?php op_smt_use_stylesheet('/opLikePlugin/css/like-smartphone.css', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/jquery.colorbox.js', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/jquery.timeline.js', 'last') ?>
<?php op_smt_use_javascript('/opTimelinePlugin/js/timeline-loader-smartphone.js', 'last') ?>
<?php op_smt_use_javascript('/opLikePlugin/js/like-smartphone.js', 'last') ?>

<script id="timelineTemplate" type="text/x-jquery-tmpl">
        <div class="timeline-post">
          <a name="timeline-${id}"></a>
          <div class="timeline-post-member-image">
            <a href="${member.profile_url}"><img src="${member.profile_image}" alt="member-image" width="23" /></a>
          </div>
          <div class="timeline-post-content">
            <div class="timeline-member-name">
              <a href="${member.profile_url}">{{if member.screen_name}} ${member.screen_name} {{else}} ${member.name} {{/if}}</a>
            </div>
            <div class="timeline-post-body" id="timeline-body-context-${id}">
              {{if null!==image_url}}
                  <img src="${image_url}" alt="timeline-images-${id}" class="timeline-post-image" width="48" height="48" />
              {{/if}}
              {{html body_html}}
            </div>
          </div>

          <div class="timeline-post-control">
            {{if already_liked==true}}
            <a id="timeline-like-link-${id}" class="timeline-like-link" data-next-action="remove" data-activity-id="${id}">いいね！を取り消す(${like_count})</a> |
            {{else}}
            <a id="timeline-like-link-${id}" class="timeline-like-link" data-next-action="add" data-activity-id="${id}">いいね！(${like_count})</a> | 
            {{/if}}
            <a href="#timeline-${id}" class="timeline-comment-link">コメントする</a> | <a href="<?php echo url_for('@homepage', array('absolute' => true)) ?>timeline/show/id/${id}">${created_at}</a>
          </div>
          <!--Like Plugin -->
          <span class="like-wrapper hide">
          <a href="/like/list/A/${id}"><span class="like-list" data-like-id="${id}">いいね！</span></a>
          <a><span class="like-cancel" data-like-id="${id}" style="display: none;">いいね！を取り消す&nbsp;</span></a>
          {{if member.self==false}}<a><span class="like-post" data-like-id="${id}" member-id="${member.id}"><i class="icon-thumbs-up"></i>&nbsp;&nbsp;&nbsp;</span></a>{{/if}}
          </span>
          </div>
          <!--Like Plugin -->
          <div class="row like-wrapper" data-like-id="${id}" data-like-target="A" member-id="${member.id}" style="text-align: center;">
          <span class="span6" style="text-align: center;"> 
          <a class="like-post">いいね！</a>
          <a class="like-cancel">いいね！を取り消す</a>
          </span>
          <span class="span6" style="text-align: center;">
          <a class="like-list"></a>
          </span>
          </div>

          <div class="timeline-post-comments" id="commentlist-${id}">

            <div id="timeline-post-comment-form-${id}" class="timeline-post-comment-form">
            <input class="timeline-post-comment-form-input" type="text" data-timeline-id="${id}" id="comment-textarea-${id}" /><button data-timeline-id="${id}" class="btn btn-primary btn-mini timeline-comment-button">投稿</button>
            </div>
            <div id="timeline-post-comment-form-loader-${id}" class="timeline-post-comment-form-loader">
              <?php echo op_image_tag('ajax-loader.gif', array()) ?>
            </div>
          </div>


        </div>
</script>

<script id="timelineCommentTemplate" type="text/x-jquery-tmpl">
            <div class="timeline-post-comment">

              <div class="timeline-post-comment-member-image">
                <a href="${member.profile_url}"><img src="${member.profile_image}" alt="" width="23" /></a>
              </div>
              <div class="timeline-post-comment-content">
                <div class="timeline-post-comment-name-and-body">
                <a href="${member.profile_url}">{{if member.screen_name}} ${member.screen_name} {{else}} ${member.name} {{/if}}</a>
                <span class="timeline-post-comment-body">
                {{html body_html}}
                </span>
                </div>
                <!-- like Plugin -->
                <span class="like-comment-wrapper hide">
                <div class="like-comment">
                <a href="/like/list/A/${id}"><span class="like-list" data-like-id="${id}">いいね！</span></a>
                <a><span class="like-cancel" data-like-id="${id}" style="display: none;">いいね！を取り消す&nbsp;</span></a>
{{if member.self==false}}<a><span class="like-post" data-like-id="${id}" member-id="${member.id}"><i class="icon-thumbs-up"></i>&nbsp;&nbsp;&nbsp;</span></a>{{/if}}
<div class="like-list-member" data-like-id="${id}"></div>
</div>
</span>
              </div>
              <div class="timeline-post-comment-control">
              {{if already_liked==true}}
              <a id="timeline-like-link-${id}" class="timeline-like-link" data-next-action="remove" data-activity-id="${id}">いいね！を取り消す(${like_count})</a> |
              {{else}}
              <a id="timeline-like-link-${id}" class="timeline-like-link" data-next-action="add" data-activity-id="${id}">いいね！(${like_count})</a> | 
              {{/if}}
              ${created_at}
              </div>
              <!-- Like Plugin -->
              <div class="row like-wrapper" data-like-id="${id}" data-like-target="A" member-id="${member.id}">
              <span class="span5" style="text-align: center;"> 
              <a class="like-post">いいね！</a>
              <a class="like-cancel">いいね！を取り消す</a>
              </span>
              <span class="span3" style="text-align: center;">
              <a class="like-list"></a>
              </span>
              </div>
            </div>
</script>
<script id="timelineLikeAddTemplate" type="text/x-jquery-tmpl">
いいね！(${$item.like_count})
</script>

<script id="timelineLikeRemoveTemplate" type="text/x-jquery-tmpl">
いいね！を取り消す(${$item.like_count})
</script>

<div style="display: none;">
<div id="timeline-warning">
  <div class="modal-header">
    <h3>投稿エラー</h3>
  </div>
  <div class="modal-body">
    <p>本文が入力されていません</p>
  </div>
</div>
</div>

<div class="row">
  <div class="gadget_header span12">SNS全体のタイムライン</div>
</div>

<div class="timeline" style="margin-left: 0px;">
  <div id="timeline-list" data-last-id="" data-loadmore-id="" style="margin-left: 0px;">
  
  </div>
</div>


<div id="timeline-list-loader" class="row span12 center show" style="margin-top: 20px; margin-bottom: 20px;">
<?php echo op_image_tag('ajax-loader.gif', array('alt' => 'Now Loading...')) ?>
</div>

<hr class="toumei">
<div class="row">
  <button class="span12 btn small" id="gorgon-loadmore">もっと読む</button>
</div>


<div class="sm2-bar-ui player">

    <div class="bd sm2-main-controls">

        <div class="sm2-inline-texture"></div>
        <div class="sm2-inline-gradient"></div>

        <div class="sm2-inline-element sm2-button-element">
            <div class="sm2-button-bd">
                <a href="#play" class="sm2-inline-button play-pause">Play / pause</a>
            </div>
        </div>

        <div class="sm2-inline-element sm2-inline-status">

            <div class="sm2-playlist">
                <div class="sm2-playlist-target">
                    <ul class="sm2-playlist-bd"></ul></div>
            </div>

            <div class="sm2-progress">
                <div class="sm2-row">
                    <div class="sm2-inline-time">0:00</div>
                    <div class="sm2-progress-bd">
                        <div class="sm2-progress-track">
                            <div class="sm2-progress-bar"></div>
                            <div class="sm2-progress-ball"><div class="icon-overlay"></div></div>
                        </div>
                    </div>
                    <div class="sm2-inline-duration">0:00</div>
                </div>
            </div>

        </div>

        <div class="sm2-inline-element sm2-button-element sm2-volume">
            <div class="sm2-button-bd">
                <span class="sm2-inline-button sm2-volume-control volume-shade"></span>
                <a href="#volume" class="sm2-inline-button sm2-volume-control">volume</a>
            </div>
        </div>

        <div class="sm2-inline-element sm2-button-element sm2-menu">
            <div class="sm2-button-bd">
                <a href="#menu" class="sm2-inline-button menu">menu</a>
            </div>
        </div>

    </div>

    <div class="bd sm2-playlist-drawer sm2-element" style="height: 0;">

        <div class="sm2-inline-texture">
            <div class="sm2-box-shadow"></div>
        </div>

        <!-- playlist content is mirrored here -->

        <div class="sm2-playlist-wrapper">

            <ul class="sm2-playlist-bd">

                <!-- standard one-line items, no extra links -->
                {% for track in list %}
                    <li><a href="{{ track['url'] }}"><strong>{{ track['artist'] }}</strong> - {{ track['title'] }}</a></li>
                {% endfor %}

            </ul>

        </div>

        <div class="sm2-extra-controls">

            <div class="bd">

                <div class="sm2-inline-element sm2-button-element">
                    <a href="#prev" title="Previous" class="sm2-inline-button previous">&lt; previous</a>
                </div>

                <div class="sm2-inline-element sm2-button-element">
                    <a href="#next" title="Next" class="sm2-inline-button next">&gt; next</a>
                </div>

            </div>

        </div>

    </div>

</div>
<link rel="stylesheet" href="/bower_components/soundmanager2/demo/bar-ui/css/bar-ui.css"/>
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/bower_components/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>
<script src="/bower_components/soundmanager2/demo/bar-ui/script/bar-ui.js"></script>
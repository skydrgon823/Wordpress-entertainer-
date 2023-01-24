If you want to modify the template you can do so in your theme

copy the template file to yourtheme/buddypress/members/featured/ directory and modify it there.

Template Usage/Loading:-
Widget:-
    view:->list checks for
        - current_theme/(buddypress/)members/featured/widget/members-loop-list.php
        - current_theme/(buddypress/)members/featured/members-loop-list.php

        - plugins/bp-featured-members/template/members-loop-list.php

  view:->slider checks for
        - current_theme/(buddypress/)members/featured/widget/members-loop-slider.php
        - current_theme/(buddypress/)members/featured/members-loop-slider.php

        - plugins/bp-featured-members/template/members-loop-slider.php

Shortcode:-
    view:->list checks for
        - current_theme/(buddypress/)members/featured/shortcode/members-loop-list.php
        - current_theme/(buddypress/)members/featured/members-loop-list.php

        - plugins/bp-featured-members/template/members-loop-list.php

  view:->slider checks for
        - current_theme/(buddypress/)members/featured/shortcode/members-loop-slider.php
        - current_theme/(buddypress/)members/featured/members-loop-slider.php

        - plugins/bp-featured-members/template/members-loop-slider.php

 Have fun!
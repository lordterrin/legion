<?php

session_start();

require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

?>

<script>

  try {
    var logged_in = "<?php echo $_SESSION["loggedin"] ?? 0; ?>";
    var user_id   = "<?php echo $_SESSION["user_id"] ?? 0; ?>";
  } catch(e) {
    var logged_in = 0;
    var user_id   = 0;
  }

</script>

<?php include 'header.php';?>
<?php include 'footer.php';?>
<?php include 'subpages/home.php';?>
<?php include 'subpages/builders.php';?>
<?php include 'preload.php';?>

<body>

  <div class="tiny_header" style="display: none;">
    <div class="th_item th_title">LTD Players Guide</div>
    <div class="th_item th_discord"><i class="fab fa-discord th_icon"></i></div>
    <div class="th_item th_home">home</div>
    <div class="th_item th_modes">game modes</div>
    <div class="th_item th_builders">builders</div>
    <div class="th_item th_units">units</div>
    <div class="th_item th_levels">levels</div>
    <div class="th_item th_filter">
      <div title="search by literally any word anywhere" id="units_table_filter_alt" class="dataTables_filter_alt">
        <label>
          <div class="filter_text_alt">Filter</div>
          <input id="th_filter" type="search">
        </label>
      </div>
    </div>
  </div>

  <div class="header">

    <div class="top_header">
      <div class="left_header">
        <div class="left_header_title">Legion TD Players Guide</div>
        <div class="left_header_subtitle">For Warcraft 3 Legion TD 9.5+ by SchachMatt</div>
      </div>
      <div class="right_header">
        <div class="left_header_item">Discord: <a target="_blank" href="https://discord.gg/n5tWRPgqJm">https://discord.gg/n5tWRPgqJm</a></div>
        <div title="Login and account creation" class="login_holder">
          <div class="login_status"></div>
          <img class="home_left" src="img/wc3_left.png">
          <img class="home_right" src="img/wc3_right.png">
        </div>
      </div>
    </div>

    <div class="subheader">
      <div id="home" class="right_header_item">
        <div class="header_image"><img src="img/home.png"></div>
        <div class="header_subtitle">Home</div>
      </div>
      <div id="modes" class="right_header_item">
        <div class="header_image"><img src="img/modes.png"></div>
        <div class="header_subtitle">Game Modes</div>
      </div>
      <div id="builders" class="right_header_item">
        <div class="header_image"><img src="img/prophet.png"></div>
        <div class="header_subtitle">Builders</div>
      </div>
      <div id="units" class="right_header_item">
        <div class="header_image"><img src="img/icetrollshaman.png"></div>
        <div class="header_subtitle">Units</div>
      </div>
      <div id="levels" class="right_header_item">
        <div class="header_image"><img src="img/draeneichieftains.png"></div>
        <div class="header_subtitle">Levels</div>
      </div>
      <!-- <div id="strategy" class="right_header_item">
        <div class="header_image"><img src="img/chaos_attack.png"></div>
        <div class="header_subtitle">Strategy</div>
      </div> -->
    </div>
  </div>

  <div class="legion_body">
    <div class="lb_holder"></div>
  </div>


  <div class="legion_footer">
    <div class="footer_item">(c)2022</div>
    <div class="footer_item" onclick="about_swal()">About</div>
    <div class="footer_item">Home</div>
  </div>

</body>
</html>


<script>

  String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  };

  var primary_game_mode     = '';
  var secondary_game_modes  = [];
  var levels_data           = [];
  var units_data            = [];

$('.th_item').click(function() {

  if ( $(this).hasClass('th_title') ) {
    $('#home').trigger('click');
    hide_tiny_header();
  } else if ( $(this).hasClass('th_discord') ) {
    window.open('https://discord.gg/n5tWRPgqJm', '_blank');
  } else if ( $(this).hasClass('th_home') ) {
    $('#home').trigger('click');
    hide_tiny_header();
  } else if ( $(this).hasClass('th_modes') ) {
    $('#modes').trigger('click');
    hide_tiny_header();
  } else if ( $(this).hasClass('th_units') ) {
    $('#units').trigger('click');
  } else if ( $(this).hasClass('th_levels') ) {
    $('#levels').trigger('click');
  }

});

/* menu icon / text hover functionality */
$(".right_header_item").hover(function(){
  $(this).find('.header_image').addClass('hi_hover');
  $(this).find('.header_subtitle').addClass('ht_hover');
  }, function(){
  $(this).find('.header_image').removeClass('hi_hover');
  $(this).find('.header_subtitle').removeClass('ht_hover');
});

$('.login_holder').click(function() {

  $('.login_holder').addClass('overflow_hidden');
  $('.home_left').addClass('left_open');
  $('.home_right').addClass('right_open');

  setTimeout(function() {
    open_login_prompt();
  }, 1800);

});

function about_swal() {
  Swal.fire({
    title: "Created out of love for a 20 year old game.",
    text : "Questions? Comments? Email me at lordterrin (at) gmail (dot) com"
  })
}

function create_new_user(user_username, user_password) {

  console.log('2: before promise');
  return new Promise((resolve, reject) => {

    console.log('2: before ajax');
    $.ajax({
      type: "POST",
      url: "api/create_user.php",
      data: {
        user_username : user_username,
        user_password : user_password,
      },
      success: function(response) {
        console.log('2: before resolve');
        resolve(response);
      }
    });

  });

}

function log_user_in(user_username, user_password) {

  console.log('2: before promise');
  return new Promise((resolve, reject) => {

    console.log('2: before ajax');
    $.ajax({
      type: "POST",
      url: "api/log_user_in.php",
      data: {
        user_username : user_username,
        user_password : user_password,
      },
      success: function(response) {
        console.log('2: before resolve');
        resolve(response);
      }
    });

  });

}

async function wait_for_new_user_creation(user_username, user_password) {

  console.log('1: before ajax call');
  const result = await create_new_user(user_username, user_password);
  console.log('1: ajax response = ' + result);

  return result;

}

async function wait_for_user_login(user_username, user_password) {

  console.log('1: before ajax call');
  const result = await log_user_in(user_username, user_password);
  console.log('1: ajax response = ' + result);

  return result;

}

$(document).on('click', '.create_account', async function() {

  user_username = $('#user_username').val();
  user_password = $('#user_password').val();

  if ( user_username.length == 0 || user_password.length == 0 ) {
    /* throw new Error goes to the .catch(err => handler */
    Swal.showValidationMessage('Enter the username and password you wish to create');
    return false;
  }

  create_user = await wait_for_new_user_creation(user_username, user_password);

  if ( create_user == 'Success!' ) {
    Swal.showValidationMessage('Success! Go ahead and log in.');
    $('.swal2-validation-message').addClass('swal_success_validation');
    $('.create_account').addClass('no_click_lowpacity');

  } else {
    reject(create_user);
  }

})

function open_login_prompt() {

  if ( logged_in == 1 ) {
    Swal.fire({
      title: "You're already logged in",
      showCancelButton: true,
      cancelButtonText : 'Logout'
    }).then((result) => {


    if (result.isConfirmed) {

    } else if ( result.isDismissed ) {
      $.ajax({
        type: "POST",
        url: "api/logout.php",
        success: function(response) {
          logged_in = 0;
          user_id   = 0;
          $('.login_status').removeClass('logged_in');
          Swal.fire({
            title: "bye."
          })
        }
      });

      $('.login_holder').removeClass('overflow_hidden');
      $('.home_left').removeClass('left_open');
      $('.home_right').removeClass('right_open');
    }

  })

    $('.login_holder').removeClass('overflow_hidden');
    $('.home_left').removeClass('left_open');
    $('.home_right').removeClass('right_open');

    return false;
  }

  swal_html = ''+
  '<div class="login_swal_holder">'+
    '<div class="login__field">'+
      '<i class="login__icon fas fa-user"></i>'+
      '<input id="user_username" type="text" class="login__input" placeholder="Username">'+
    '</div>'+
    '<div class="login__field">'+
      '<i class="login__icon fas fa-lock"></i>'+
      '<input id="user_password" type="password" class="login__input" placeholder="Password">'+
    '</div>'+
    '<div class="create_account">create account</div>'+
  '</div>';

  Swal.fire({
    title: 'Login',
    html: swal_html,
    showCancelButton: true,
    confirmButtonText: 'Login',
    backdrop : true,
    showLoaderOnConfirm: true,
    allowOutsideClick: true,
    preConfirm: function (foo) {

      console.log('0: 1');

      return new Promise(async function (resolve, reject) {

        console.log('0: 2');
        user_username = $('#user_username').val();
        user_password = $('#user_password').val();

        if ( user_username.length == 0 || user_password.length == 0 ) {
          /* throw new Error goes to the .catch(err => handler */
          reject('Enter a username and password.');
          return false;
        }

        login_results = await wait_for_user_login(user_username, user_password);

        console.log('0: should not see this until ajax is done');
        console.log('0: log_user_in', login_results);

        if ( login_results == 'Success!' ) {

          resolve(login_results);

          $('.login_holder').removeClass('overflow_hidden');
          $('.home_left').removeClass('left_open');
          $('.home_right').removeClass('right_open');

          logged_in = 1;
          user_id   = "<?php echo $_SESSION["user_id"] ?? 0; ?>";
          $('.login_status').addClass('logged_in');

          Swal.fire({
            title: 'Sweet.'
          });

        } else {
          reject(login_results);
        }


      }).catch(err => {
        Swal.showValidationMessage(`${err}`);
        return false;
      })


    } //close preConfirm

  }).then((result) => {

    console.log('pc 4');

    console.log(result);

    if (result.isConfirmed) {
      // Swal.fire({
      //   title: 'Sweet.'
      // })
    } else if ( result.isDismissed ) {
      $('.login_holder').removeClass('overflow_hidden');
      $('.home_left').removeClass('left_open');
      $('.home_right').removeClass('right_open');
    }
  })

}

$(document).on('click', '.right_header_item', function() {

  /* get section*/
  var id = $(this).attr('id');

  if ( id == 'home' ) {
    toggle_visibility('show_home');
  } else if ( id == 'modes' ) {
    toggle_visibility('show_modes');
  } else if ( id == 'builders' ) {
    toggle_visibility('show_builders');
  } else if ( id == 'units' ) {
    toggle_visibility('show_units');
  } else if ( id == 'levels' ) {
    toggle_visibility('pre_show_levels');
  } else if ( id == 'strategy' ) {
    toggle_visibility('show_strategy');
  }

});

function toggle_visibility(id) {
  $('.legion_body').fadeOut(300, function() {
    var fnstring = id;
    var fn = window[fnstring];
    if (typeof fn === "function") fn();
    $('.legion_body').fadeIn(1000, function() {
    });
  });

}

function show_home() {

  html = ''+
  '<div class="lb_title">'+
  'Home'+
  '</div>'+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      'The Legion TD Players Guide exists to help both new and experienced players understand and build their skills in the Warcraft 3 custom <span>Legion TD</span> maps created by SchachMatt.  Much of the content here is applicable to other versions of Legion TD as well.'+
    '</div>'+

    '<div class="lb_text">'+
      'In Legion TD, teams of players build towers to defend against waves of units that spawn in an attempt to reach and kill each team\'s king.  Each player builds towers prior to the start of the round, each tower with a different attack type, defense type, and set of abilities. The game ends when one team\'s king loses all of its health.'+
    '</div>'+

    '<div class="lb_text">'+
      'To download the most recent version of the map, chat with other players, provide suggestions, or discuss bugs, please <a href="https://discord.gg/n5tWRPgqJm"> visit the discord</a>'+
    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

}

function show_modes() {

  primary_game_mode = '';
  secondary_game_modes = [];

  html = ''+
  '<div class="lb_title">'+
  'Game Modes'+
  '</div>'+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      'Each game of Legion TD has its mode set by the host of the game, who can pick from a few <span>primary</span> game modes, as well as several <span>secondary</span> game modes. Use the tool below to combine various modes and see what they do. You can also copy your gamemode to your clipboard.'+
    '</div>'+

    '<div class="lb_text">'+

      '<div class="lb_modes_holder">'+

        '<div class="current_game_mode"></div>'+

        '<div class="mode_output">'+

          '<div id="mo_primary" class="mo_primary">'+
            '<div class="mo_primary_text"></div>'+
          '</div>'+

          '<div id="mo_secondary" class="mo_secondary">'+
            '<div class="mo_secondary_text">'+

              // cc/ac/lg/mi/ez/x3/x4


              '<div class="st_item st_item_off" id="s_cc">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">cc</div>'+
                '</div>'+
                '<div class="st_text">Players can <span>c</span>hallenge a <span>c</span>hampion each round for extra gold. This makes the round much more difficult to beat.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_ac">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">ac</div>'+
                '</div>'+
                '<div class="st_text">Starting at level 6, players <span>a</span>lways <span>c</span>hallenge a champion. This is a very difficult game mode.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_lg">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">lg</div>'+
                '</div>'+
                '<div class="st_text">Levels 21-29 are enabled for a <span>l</span>ong <span>g</span>ame. By default, these rounds are skipped.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_mi">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">mi</div>'+
                '</div>'+
                '<div class="st_text"><span>M</span>irror <span>m</span>ode. Opposite colors have the same rolls each round (in -pr), or the same rerolls (in -ph), e.g. - red = yellow, teal = green.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_ez">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">ez</div>'+
                '</div>'+
                '<div class="st_text"><span>Easy</span> mode. All creeps spawn with 85% health.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_x3">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">x3</div>'+
                '</div>'+
                '<div class="st_text">In <span>x3</span>, three units are sent from the barracks to the other team each time you buy summons with lumber. </div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_x4">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">x4</div>'+
                '</div>'+
                '<div class="st_text">In <span>x4</span>, 1 unit is sent to <span>each opposing lane</span> whenever you buy summons with lumber. These sends are stronger, but much more expensive.</div>'+
              '</div>'+

              '<div class="st_item st_item_off" id="s_ur">'+
                '<div class="st_title">'+
                  '<div class="st_title_text">ur</div>'+
                '</div>'+
                '<div class="st_text">In <span>unranked</span> mode, no ELO is counted after the conclusion of the match.</div>'+
              '</div>'+

            '</div>'+
          '</div>'+

        '</div>'+

        '<div class="modes_holder">'+

          '<div class="primary_modes">'+
            '<div id="p_ap" class="p_mode">-ap</div>'+
            '<div id="p_pr" class="p_mode">-pr</div>'+
            '<div id="p_ph" class="p_mode">-ph</div>'+
            '<div id="p_hb" class="p_mode">-hb</div>'+
          '</div>'+
          '<div class="secondary_modes">'+
            '<div id="sm_cc" class="s_mode">cc</div>'+
            '<div id="sm_ac" class="s_mode">ac</div>'+
            '<div id="sm_lg" class="s_mode">lg</div>'+
            '<div id="sm_mi" class="s_mode">mi</div>'+
            '<div id="sm_ez" class="s_mode">ez</div>'+
            '<div id="sm_x3" class="s_mode">x3</div>'+
            '<div id="sm_x4" class="s_mode">x4</div>'+
            '<div id="sm_ur" class="s_mode">ur</div>'+
          '</div>'+

        '</div>'+

      '</div>'+

    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

  $('.legion_body').fadeIn(500);
}

$(document).on('click', '.copy_gamemode', function() {
  copyTextToClipboard(primary_game_mode + '' + sgm_string);
  alertify.success('Copied to clipboard!');
});

function fallbackCopyTextToClipboard(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;

  // Avoid scrolling to bottom
  textArea.style.top = "0";
  textArea.style.left = "0";
  textArea.style.position = "fixed";

  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Fallback: Copying text command was ' + msg);
  } catch (err) {
    console.error('Fallback: Oops, unable to copy', err);
  }

  document.body.removeChild(textArea);
}
function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(function() {
    console.log('Async: Copying to clipboard was successful!');
  }, function(err) {
    console.error('Async: Could not copy text: ', err);
  });
}

function set_current_game_mode() {

  sgm_string        = secondary_game_modes.join('');
  var copy_icon     = '<div class="copy_gamemode"><span class="material-icons">content_copy</span></div>';
  current_game_mode = "<span>" + primary_game_mode + '</span>' + sgm_string

  $('.current_game_mode').html(current_game_mode  + '' + copy_icon);

}

$(document).on('click', '.p_mode', function() {

  var p_mode = $(this).text();
  var p_mode_html = '';

  deselect_primary_mode();

  if ( p_mode == '-ap' ) {

    p_mode_html = ''+
    '-ap stands for <span>all pick</span>. Each player chooses a builder at the start of the game and builders towers from that builder.  You can change builders up to 6 times, but it becomes more expensive each time you do it.';
    select_primary_mode('p_ap');

  } else if ( p_mode == '-pr' ) {

    p_mode_html = ''+
    '-pr stands for <span>prophet random</span>, which provides all players with the prophet builder, who generates 6 towers at random across all builders. In <span>-pr</span> mode specifically, your builder gets 6 new random towers every round.';
    select_primary_mode('p_pr');

  } else if ( p_mode == '-ph' ) {

    p_mode_html = ''+
    '-ph stands for <span>prophet</span>, which provides all players with the prophet builder - who generates 6 towers at random across all builders. In <span>-ph</span> mode specifically, your towers do not change round to round unless you manually <span>reroll</span>.  The cost of rerolling becomes more expensive each time you do it.';
    select_primary_mode('p_ph');

  } else if ( p_mode == '-hb' ) {

    p_mode_html = ''+
    '-hb stands for <span>hybrid builder</span>. In this mode, your builder can build 6 towers, and when those towers finish building, they turn into a random tower from that tier.  This mode is completely random and there is no way to know which tower you are building prior to build the tower.';
    select_primary_mode('p_hb');
  }

  $('.mo_primary_text').html(p_mode_html);

  set_current_game_mode();

});


$(document).on('click', '.s_mode', function() {

  var s_mode = $(this).text();
  var s_mode_html = '';

  // cc/ac/lg/mi/ez/x3/x4

  if ( s_mode == 'cc' ) {

    if ( $('#s_cc').hasClass('st_item_on') ) {
      $('#s_cc').removeClass('st_item_on');
      $('#sm_cc').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'ac' ) {

    if ( $('#s_ac').hasClass('st_item_on') ) {
      $('#s_ac').removeClass('st_item_on');
      $('#sm_ac').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'lg' ) {

    if ( $('#s_lg').hasClass('st_item_on') ) {
      $('#s_lg').removeClass('st_item_on');
      $('#sm_lg').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'mi' ) {

    if ( $('#s_mi').hasClass('st_item_on') ) {
      $('#s_mi').removeClass('st_item_on');
      $('#sm_mi').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'ez' ) {

    if ( $('#s_ez').hasClass('st_item_on') ) {
      $('#s_ez').removeClass('st_item_on');
      $('#sm_ez').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'x3' ) {

    if ( $('#s_x3').hasClass('st_item_on') ) {
      $('#s_x3').removeClass('st_item_on');
      $('#sm_x3').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'x4' ) {

    if ( $('#s_x4').hasClass('st_item_on') ) {
      $('#s_x4').removeClass('st_item_on');
      $('#sm_x4').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  } else if ( s_mode == 'ur' ) {

    if ( $('#s_ur').hasClass('st_item_on') ) {
      $('#s_ur').removeClass('st_item_on');
      $('#sm_ur').removeClass('s_class_selected');
      remove_secondary_game_mode(s_mode);
    } else {
      add_secondary_game_mode(s_mode);
    }

  }

  set_current_game_mode();

});

function add_secondary_game_mode(mode) {

  /* make sure we're not adding modes that don't go together */
  cc_exists = 0;
  ac_exists = 0;
  x3_exists = 0;
  x4_exists = 0;

  for ( var x=0; x<secondary_game_modes.length; x++ ) {

    if ( secondary_game_modes[x] == 'cc' ) {
      cc_exists = 1;
    }

    if ( secondary_game_modes[x] == 'ac' ) {
      ac_exists = 1;
    }

    if ( secondary_game_modes[x] == 'x3' ) {
      x3_exists = 1;
    }

    if ( secondary_game_modes[x] == 'x4' ) {
      x4_exists = 1;
    }

  }

  if ( cc_exists == 1 && mode == 'ac' ) {
    alertify.error('<span>cc</span> and <span>ac</span> cannot be used together');
    return false;
  }

  if ( ac_exists == 1 && mode == 'cc' ) {
    alertify.error('<span>cc</span> and <span>ac</span> cannot be used together');
    return false;
  }

  if ( x3_exists == 1 && mode == 'x4' ) {
    alertify.error('<span>x3</span> and <span>x4</span> cannot be used together');
    return false;
  }

  if ( x4_exists == 1 && mode == 'x3' ) {
    alertify.error('<span>x3</span> and <span>x4</span> cannot be used together');
    return false;
  }

  secondary_game_modes.push(mode);

  if ( mode == 'cc' ) {
    $('#s_cc').addClass('st_item_on');
    $('#sm_cc').addClass('s_class_selected');
  } else if ( mode == 'ac' ) {
    $('#s_ac').addClass('st_item_on');
    $('#sm_ac').addClass('s_class_selected');
  } else if ( mode == 'lg' ) {
    $('#s_lg').addClass('st_item_on');
    $('#sm_lg').addClass('s_class_selected');
  } else if ( mode == 'mi' ) {
    $('#s_mi').addClass('st_item_on');
    $('#sm_mi').addClass('s_class_selected');
  } else if ( mode == 'ez' ) {
    $('#s_ez').addClass('st_item_on');
    $('#sm_ez').addClass('s_class_selected');
  } else if ( mode == 'x3' ) {
    $('#s_x3').addClass('st_item_on');
    $('#sm_x3').addClass('s_class_selected');
  } else if ( mode == 'x4' ) {
    $('#s_x4').addClass('st_item_on');
    $('#sm_x4').addClass('s_class_selected');
  } else if ( mode == 'ur' ) {
    $('#s_ur').addClass('st_item_on');
    $('#sm_ur').addClass('s_class_selected');
  }

}

function remove_secondary_game_mode(mode) {

  mode_index = null;

  for ( var x=0; x<secondary_game_modes.length; x++ ) {
    if ( secondary_game_modes[x] == mode ) {
      mode_index = x;
    }
  }

  if ( mode_index != null ) {
    secondary_game_modes.splice(mode_index,1);
  }

}

function deselect_primary_mode() {
  $('.p_mode').removeClass('p_class_selected');
}

function select_primary_mode(id) {
  $('#' + id).addClass('p_class_selected');
  primary_game_mode = id.replace('p_', '-');
}

function get_attack_photo(item) {

  if ( item ) {
    item = item.toLowerCase();
  } else {
    item = 'none';
  }

  return '<img src="img/attack_' + item + '.png">';
}

function get_armor_photo(item) {

  if ( item ) {
    item = item.toLowerCase();
  } else {
    item = 'none';
  }

  return '<img src="img/armor_' + item + '.png">';
}

function get_upgrade_photo(unit_name) {

  if ( unit_name ) {

    if ( unit_name.includes(',') ) {

      return_string = '<div class="multiple_things">';

      units_array = unit_name.split(',');

      for ( var x=0; x<units_array.length; x++ ) {

        unit = units_array[x].replace(/ /g, '').toLowerCase();
        return_string += '<img src="img/'+ unit +'.png">';

      }

      return_string += '</div>';

      return return_string;

    } else {

      unit_name = unit_name.replace(/ /g, '').toLowerCase();
      return '<img src="img/'+ unit_name +'.png">';
    }

  } else {
    return '<img src="img/none.png">';
  }

}


function get_creep_photo(creep_name) {

  if ( creep_name ) {
    creep_name = creep_name.replace(/ /g, '').toLowerCase();
    return '<img src="img/'+ creep_name +'.png">';
  } else {
    return '<img src="img/none.png">';
  }

}

function get_strategy_text(level) {

  var output = '';

  if ( level == 1 ) {

    output = 'Level 1 should be an easy level for everyone. Focus first on building enough <span>value</span> to hold the wave, then on planning what you will need for levels 2, 3, and 4. Use your leftover gold either to build wisps, or to <span>spam</span> any low cost unupgraded units for later rounds. In most modes, no player has <span>lumber</span> on level 1.<div class="send_threat threat_low">This round poses a very low risk of additional sends</div>';

  } else if ( level == 2 ) {

    output = 'While still on the easier side, players will benefit from <span>normal</span> attack units with <span>heavy</span> armor. Use this round to continue planning for rounds 3 and 4, building wisps if you can hold. <div class="send_threat threat_low">This round poses a very low risk of additional sends</div>';

  } else if ( level == 3 ) {

    output = 'Level 3 is where the game truly begins, as the opposing team could have been building several wisps during rounds 1 and 2, and could <span>send hard</span> this round.  This is a much higher probability if your team <span>leaked</span> on round 1 or 2, as they will have had more time to <span>lumber</span>. In general, the same units that were good against rounds 1 and 2 are good here. If you haven\'t already, start planning for level 4 here! <div class="send_threat threat_medium">This round poses a medium risk of additional sends</div>';

  } else if ( level == 4 ) {

    output = 'Level 4 generally requires units that deal magic damage. The level is very difficult and many players will leak here because levels 1-3 were much easier and did not require units that dealt magic damage. For this reason, level 4 is generally the first big send round for both teams. A good general goal by level 4 is to have at least 4 wisps, with 1 lumber carry upgrade. <div class="send_threat threat_high">This round poses a very high risk of additional sends!</div>';

  } else if ( level == 5 ) {

    output = 'Level 5 is the first air round, and the first real round where piercing attack is important.  Units clumb on tanks and take them out much more quickly, so any units that slow enemy movement speed are incredibly valuable.  Up to this point, heavy armor has been wonderful, but it\'s the worst armor type on level 5, so tanks that do not have heavy armor are beneficial here. Be very careful increasing your wisp count if the enemy team did not send on levels 3 or 4, as that can mean they are planning a mega-send on level 5. <div class="send_threat threat_medium">This round poses a medium to high risk of additional sends</div>';

  } else if ( level == 6 ) {

    output = 'Level 6 is generally an easier round, because in most games, teams are saving lumber for level 7. Siege damage is good here, but don\'t overdo it as it is not as effective again until level 11. <div class="send_threat threat_low">This round poses a low to medium risk of additional sends</div>';

  } else if ( level == 7 ) {

    output = 'Level 7 is the first make or break round where teams will oftentimes try to end the game.  Many teams will send on level 4, then save lumber for a massive mega-send on level 7 in a attempt to completely kill the other team.  This level requires strong tanks and good piercing damage, and units that slow are incredibly helpful here as well. Having at least 9 wisps, with 1-2 lumber upgrades by this point is a good general goal to ensure you are earning enough lumber. <div class="send_threat threat_high">This round poses an extremely high risk of additional sends</div>';

  } else if ( level == 8 ) {

    output = 'Most tanks that have been good up to this point have heavy armor, which again is bad on level 8 due to their magic attack.  While normal damage is strong here, level 8 can eat through most front lines very quickly if you have not diversified your front line at all.  While not an incredibly difficult level, be wary of a massive send if the enemy team did not send on level 7.<div class="send_threat threat_medium">This round poses a medium to high risk (depending) of additional sends</div>';

  } else if ( level == 9 ) {

    output = 'Level 9 is the last level before the boss round, and is a relatively easy level to beat which allows teams to prepare their armies for level 10.  If the enemy team did not send on levels 7 or 8, they could have been saving for a <span>9 bomb</span>, where they will send a massive amount of units here to try and catch opponents off guard, or exploit opponents who <span>cc</span>.  Be wary of this, and think twice about challening champions here if you believe there is a significant risk of the enemy team sending! You should generally not build wisps or lumber upgrades on this round, opting to build up your defenses for level 10 instead. <div class="send_threat threat_low_high">This round can pose anywhere from low to high risk of additional sends</div>';

  } else if ( level == 10 ) {

    output = 'Level 10 is the first (and usually only) boss round of the game, and many games end here.  In order to beat this round, you generally need a strong front line of tanks and lots of <span>single-target piercing damage</span>.  Units that deal splash damage are no longer strong on this round, while units that slow enemy movment and attack speed are very helpful.  Because level 10 is so hard by itself, teams will generally send the largest amount of additional sends on this round in an attempt to kill their opponent\'s king.  You should be sending the largest and best units possible to the enemy team here in an attempt to kill them.  A good general goal by level 10 is to have 10 wisps, with at least 5 lumber carry upgrades.  <div class="send_threat threat_high">This round poses a maximum risk of additional sends!</div>'

  }


  if ( output == '' ) {
    return 'No strategy has been written for this level yet.';
  } else {
    return output;
  }

}

function get_good_units(level) {

  var output = '';
  var level_good_var = level + '_good';

  var full_good_output = [];

  for ( var x=0 ; x<units_data.length; x++ ) {

    try {
      var unit_name   = units_data[x].unit_name;
      var unit_image  = unit_name.replace(/ /g, '').toLowerCase();
      var count       = units_data[x][level_good_var] ?? 0;
      var count_text  = '';

      if ( count == 1 ) {
        count_text = 'player recommends';
      } else {
        count_text = 'players recommend';
      }

      if ( count > 0 ) {

        output_object = {
          'unit_name'   : unit_name,
          'unit_image'  : unit_image,
          'count'       : count,
          'count_text'  : count_text
        };

        full_good_output.push(output_object);

      }

    } catch (e) {
      return '';
    }

  }

  /* sort most recommended to least recommended, then return only the top 10 items */
  full_good_output.sort(function(a, b) {
    return parseFloat(b.count) - parseFloat(a.count);
  });

  top_ten_good_units = full_good_output.slice(0, 10);

  for ( var i=0; i<top_ten_good_units.length; i++ ) {
    output += ''+
    '<div class="good_unit_holder">'+
      '<div title="'+ top_ten_good_units[i].unit_name +'" class="unit_image">'+
        '<img src="img/'+ top_ten_good_units[i].unit_image +'.png">'+
      '</div>'+
      '<div title="'+ top_ten_good_units[i].count +' ' + top_ten_good_units[i].count_text + ' '+ top_ten_good_units[i].unit_name +'" class="unit_count_holder">'+
        '<div class="unit_count">'+ top_ten_good_units[i].count + '</div>'+
      '</div>'+
    '</div>';
  }

  return output;

}

function get_bad_units(level) {

  var output = '';
  var level_bad_var = level + '_bad';

  var full_bad_output = [];

  for ( var x=0 ; x<units_data.length; x++ ) {

    var unit_name   = units_data[x].unit_name;
    var unit_image  = unit_name.replace(/ /g, '').toLowerCase();
    var count       = units_data[x][level_bad_var] ?? 0;
    var count_text  = '';

    if ( count == 1 ) {
      count_text = 'player recommends against';
    } else {
      count_text = 'players recommend against';
    }

    if ( count > 0 ) {

      output_object = {
        'unit_name'   : unit_name,
        'unit_image'  : unit_image,
        'count'       : count,
        'count_text'  : count_text
      };

      full_bad_output.push(output_object);

    }

  }

  /* sort most recommended to least recommended, then return only the top 10 items */
  full_bad_output.sort(function(a, b) {
    return parseFloat(b.count) - parseFloat(a.count);
  });

  top_ten_bad_units = full_bad_output.slice(0, 10);

  for ( var i=0; i<top_ten_bad_units.length; i++ ) {
    output += ''+
    '<div class="bad_unit_holder">'+
      '<div title="'+ top_ten_bad_units[i].unit_name +'" class="unit_image">'+
        '<img src="img/'+ top_ten_bad_units[i].unit_image +'.png">'+
      '</div>'+
      '<div title="'+ top_ten_bad_units[i].count +' ' + top_ten_bad_units[i].count_text + ' '+ top_ten_bad_units[i].unit_name +'" class="unit_count_holder">'+
        '<div class="unit_count">'+ top_ten_bad_units[i].count + '</div>'+
      '</div>'+
    '</div>';
  }

  return output;

}

function get_tier_photo(tier) {

  if ( tier ) {
    tier = tier.toLowerCase();
  } else {
    tier = 'none';
  }

  return '<img src="img/tier' + tier +'.png">';
}

function get_builder_photo(builder) {

  if ( builder ) {
    builder = builder.toLowerCase();
  } else {
    builder = 'none'
  }

  return '<img src="img/builder_' + builder + '.png">';

}

function show_units() {

  html = ''+
  '<div class="lb_title">'+
  'Units'+
  '</div>'+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      'Legion TD contains over 100 units that you can build to defend your lane against waves of units, and that can be quite overwhelming to both new and advanced players.  Below is a list of all units in the game.<div class="rate_message_holder"><div class="rate_message">Understand Legion TD well?  Help the community by rating units up or down for any level, or writing descriptions and notes.  Ratings are visible in the <span>levels</span> page for all users</div></div>'+
    '</div>'+

    '<div class="lb_text">'+
      '<table id="units_table" class="legion_table"></table>';
    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

  $.fn.dataTable.ext.errMode = 'throw';

  if ( $.fn.dataTable.isDataTable( '#levels_table') ) {
    levels_table.draw();
    levels_table.destroy();
  }

  units_table = $('#units_table').DataTable({
    'paging'      : false,
    'bFilter'     : true,
    'bSort'       : false,
    'data'        : units_data,
    'language'    : {
      "search": '<div class="filter_text">Filter</div>'
    },
    'columnDefs'  : [
      {
        'targets' : 0,
        'data'    : function ( data, type, val, meta) {

          var builder         = data.builder;
          var unit_name       = data.unit_name;
          var unit_id         = data.id;
          var upgrades_from   = (data.upgrades_from == null ? '' : data.upgrades_from);
          var upgrades_to     = (data.upgrades_to == null ? '' : data.upgrades_to);
          var damage_type     = data.damage_type;
          var armor_type      = data.armor_type;
          var tier            = data.tier;

          var unit_photo            = get_creep_photo(unit_name);
          var damage_photo          = get_attack_photo(damage_type);
          var armor_photo           = get_armor_photo(armor_type);
          var upgrades_to_photo     = get_upgrade_photo(upgrades_to);
          var upgrades_from_photo   = get_upgrade_photo(upgrades_from);
          var tier_photo            = get_tier_photo(tier);
          var builder_photo         = get_builder_photo(builder);

          var unit_strategy         = data.basic_strategy;

          var final_upgrades_to     = get_final_creep_name(upgrades_to);
          var final_upgrades_from   = get_final_creep_name(upgrades_from);

          var rate_unit_html = ''

          if ( unit_strategy == null ) {
            unit_strategy = ''+
            '<div class="no_strategy_holder">'+
              '<div id="ns_'+ unit_id +'" data-unit-id="'+ unit_id +'" class="no_strategy_text">No basic strategy exists yet for this unit.  If you want to help the community, consider <span>adding one</span>!</div>'+
              '<div class="new_strategy_holder textarea_hidden">'+
                '<textarea style="resize: none;" class="new_strategy_textarea" id="text_'+ unit_id +'" name="text_'+ unit_id +'" rows="4" cols="50"></textarea>'+
                '<div class="textarea_buttons_holder">'+
                  '<div class="textarea_button"><i id="confirm_'+ unit_id +'" class="far fa-check-square confirm_button"></i></div>'+
                  '<div class="textarea_button"><i id="cancel_'+ unit_id +'" class="far fa-window-close cancel_button"></i></div>'+
                '</div>'+
              '</div>'+
            '</div>';

          }

          rate_unit_html += '<div class="rate_unit_section">';

          for ( var p=1; p<11; p++ ) {
            rate_unit_html += ''+
            '<div class="rate_unit_holder">'+
              '<div class="rate_unit_level">' + p + '</div>'+
              '<div class="rate_buttons_holder">'+
                '<div id="up_'+ unit_id +'_'+ p +'" onclick="rate_up(this)" data-unit-id="'+ unit_id +'" data-unit="'+ unit_name +'" data-level="'+p+'" title="This unit is good on level '+ p +'" class="rate_up">'+
                  '<span class="material-icons unrated">arrow_upward</span>'+
                '</div>'+
                '<div id="down_'+ unit_id +'_'+ p +'" onclick="rate_down(this)" data-unit-id="'+ unit_id +'" data-unit="'+ unit_name +'" data-level="'+p+'" title="This unit is bad on level '+ p +'" class="rate_down">'+
                  '<span class="material-icons unrated">arrow_downward</span>'+
                '</div>'+
              '</div>'+
            '</div>';
          }

          rate_unit_html += '</div><div class="rate_unit_section">';

          for ( var p=11; p<21; p++ ) {
            rate_unit_html += ''+
            '<div class="rate_unit_holder">'+
              '<div class="rate_unit_level">' + p + '</div>'+
              '<div class="rate_buttons_holder">'+
                '<div id="up_'+ unit_id +'_'+ p +'" onclick="rate_up(this)" data-unit-id="'+ unit_id +'" data-unit="'+ unit_name +'" data-level="'+p+'" class="rate_up">'+
                  '<span class="material-icons unrated">arrow_upward</span>'+
                '</div>'+
                '<div id="down_'+ unit_id +'_'+ p +'" onclick="rate_down(this)" data-unit-id="'+ unit_id +'" data-unit="'+ unit_name +'" data-level="'+p+'" class="rate_down">'+
                  '<span class="material-icons unrated">arrow_downward</span>'+
                '</div>'+
              '</div>'+
            '</div>';
          }

          /*
          rate_unit_html += '</div><div class="rate_unit_section">';

          for ( var p=21; p<31; p++ ) {
            rate_unit_html += ''+
            '<div class="rate_unit_holder">'+
              '<div class="rate_unit_level">' + p + '</div>'+
              '<div class="rate_buttons_holder">'+
                '<div class="rate_up"><i class="fas fa-thumbs-up unrated"></i></div>'+
                '<div class="rate_down"><i class="fas fa-thumbs-down unrated"></i></div>'+
              '</div>'+
            '</div>';
          }

          */

          rate_unit_html += '</div>';



          output = ''+
          '<div class="levels_master_holder">'+

            '<div class="levels_item_holder">'+

              '<div class="levels_item">'+
                '<div class="unit_name_holder">'+
                  '<div class="levels_item_title">'+ unit_name +'</div>'+
                  '<div class="levels_item_image">'+ unit_photo +'</div>'+
                  '<div class="levels_item_subtitle"></div>'+
                '</div>'+
              '</div>'+

              '<div class="levels_item">'+

                '<div class="item">'+
                  '<div class="levels_item_title">Builder</div>'+
                  '<div class="levels_item_image">'+ builder_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ builder +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Damage Type</div>'+
                  '<div class="levels_item_image">'+ damage_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ damage_type +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Armor Type</div>'+
                  '<div class="levels_item_image">'+ armor_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ armor_type +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Upgrades From</div>'+
                  '<div class="upgrade_photos">'+ upgrades_from_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ final_upgrades_from +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Upgrades Into</div>'+
                  '<div class="upgrade_photos">'+ upgrades_to_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ final_upgrades_to +'</div>'+
                '</div>'+

              '</div>'+

            '</div>'+

            '<div class="levels_item_holder">'+
              '<div class="levels_item_column">'+
                '<div id="strategy_'+ unit_id +'" class="strategy_title">Basic Unit Strategy <span title="edit this!" class="material-icons edit_strategy">edit</span></div>'+
                '<div class="strategy_text">'+ unit_strategy +'</div>'+
              '</div>'+
            '</div>'+

            '<div class="levels_item_holder">'+
              '<div class="rate_unit_master_holder">'+
                rate_unit_html +
              '</div>'+
            '</div>'+

          '</div>';

          return output;
        }
      },
    ],
    //'order'       : [[0, 'asc']],
    'info'        : false,
  });

  $('.legion_body').fadeIn(500);

}

$(document).on('click', '.no_strategy_text', function() {

  var this_unit_id = $(this).data('unitId');

  $(this).addClass('textarea_hidden');
  $('#text_' + this_unit_id).parent().addClass('textarea_shown');

});

$(document).on('click', '.cancel_button_alt', function() {

  var this_unit_id = $(this).attr('id').replace('cancel_', '');
  $('#strategy_' + this_unit_id).parent().find('.strategy_text').removeClass('textarea_hidden');
  $('#strategy_' + this_unit_id).parent().find('.new_strategy_holder').remove();

});

$(document).on('click', '.confirm_button_alt', function() {

  var this_unit_id = $(this).attr('id').replace('confirm_', '');
  var new_unit_text = $('#text_' + this_unit_id).val();

  $.ajax({
    type: "POST",
    url: "api/change_unit_text.php",
    data: {
      new_unit_text : new_unit_text,
      unit_id       : this_unit_id,
    },
    success: function(response) {

      foo = response;

      if ( foo == 'Please login first' ) {
        alertify.error('Please login first');
      }

      $('#strategy_' + this_unit_id).parent().find('.strategy_text').removeClass('textarea_hidden');
      $('#strategy_' + this_unit_id).parent().find('.strategy_text').text(new_unit_text);
      $('#strategy_' + this_unit_id).parent().find('.new_strategy_holder').remove();

    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(xhr.responseText);
    }
  });

});

$(document).on('click', '.cancel_button', function() {

  var this_unit_id = $(this).attr('id').replace('cancel_', '');
  $('#text_' + this_unit_id).parent().removeClass('textarea_shown');
  $('#ns_' + this_unit_id).removeClass('textarea_hidden');

});

$(document).on('click', '.confirm_button', function() {

  var this_unit_id = $(this).attr('id').replace('confirm_', '');
  var new_unit_text = $('#text_' + this_unit_id).val();

  console.log('this_unit_id', this_unit_id);
  console.log('new_unit_text', new_unit_text);

  $.ajax({
    type: "POST",
    url: "api/change_unit_text.php",
    data: {
      new_unit_text : new_unit_text,
      unit_id       : this_unit_id,
    },
    success: function(response) {
      foo = response;
      console.log(foo);

      if ( foo == 'Please login first' ) {
        alertify.error('Please login first');
      }

      $('#strategy_' + this_unit_id).parent().find('.no_strategy_text').removeClass('textarea_hidden');
      $('#strategy_' + this_unit_id).parent().find('.no_strategy_text').text(new_unit_text);
      $('#strategy_' + this_unit_id).parent().find('.new_strategy_holder').remove();

    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(xhr.responseText);
    }
  });

});


function fooasdf(foo) {



}

$(document).on('click', '.edit_strategy', function() {

  var existing_strategy = $(this).parent().parent().find('.strategy_text').text();
  var unit_id = $(this).parent().attr('id').replace('strategy_', '');

  $(this).parent().parent().find('.strategy_text').addClass('textarea_hidden');
  $(this).parent().parent().append('<div class="new_strategy_holder">'+
    '<textarea style="resize: none;" class="new_strategy_textarea" id="text_'+ unit_id +'" name="text_'+ unit_id +'" rows="4" cols="50">'+ existing_strategy +'</textarea>'+
    '<div class="textarea_buttons_holder">'+
      '<div class="textarea_button"><i id="confirm_'+ unit_id +'" class="far fa-check-square confirm_button_alt"></i></div>'+
      '<div class="textarea_button"><i id="cancel_'+ unit_id +'" class="far fa-window-close cancel_button_alt"></i></div>'+
    '</div>'+
  '</div>');

});

function rate_up(data) {

  this_item = data;
  unit_id   = data.dataset.unitId;
  html_id   = data.id;
  level     = data.dataset.level;
  icon      = $('#' + html_id).find('span');
  rating    = 'good';

  if ( $(icon).hasClass('uprated') ) {
    $(icon).removeClass('uprated');
    change_unit_rating(unit_id, level, rating, 'remove');
  } else {
    $(icon).addClass('uprated');
    change_unit_rating(unit_id, level, rating, 'add');
    // add ajax call here
  }


}

function rate_down(data) {

  this_item = data;
  unit_id   = data.dataset.unitId;
  html_id   = data.id;
  level     = data.dataset.level;
  icon      = $('#' + html_id).find('span');
  rating    = 'bad';

  if ( $(icon).hasClass('downrated') ) {
    $(icon).removeClass('downrated');
    change_unit_rating(unit_id, level, rating, 'remove');
  } else {
    $(icon).addClass('downrated');
    change_unit_rating(unit_id, level, rating, 'add');
  }

}

function change_unit_rating(unit_id, level, rating, action) {

  /* this function takes in a mysql unit_id, an integer level, a rating of 'up' or 'down', and an action to 'add' or 'remove' */

  console.log(unit_id, level, rating, action);
  //return false;

  $.ajax({
    type: "POST",
    url: "api/change_unit_rating.php",
    data: {
      unit_id : unit_id,
      level   : level,
      rating  : rating,
      action  : action,
    },
    success: function(response) {
      foo = response;
      console.log(foo);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(xhr.responseText);
    }
  });

}

function pre_show_levels() {

  $.ajax({
    url: "api/pull_units.php",
    success: function(result){
        try {

          units_data = JSON.parse(result);

          show_levels();

      } catch (e) {
        units_data = [];

        show_levels();


      }
    }}
  );

}

function get_final_creep_name(creep_name) {

  if ( creep_name.includes(',') ) {
    creep_string = '';
    creep_array = creep_name.split(',');
    for ( var x=0; x<creep_array.length; x++ ) {
      creep_string += '<div class="multiple_upgrades">' + creep_array[x] + '</div>';
    }
    return creep_string;
  } else {
    return creep_name;
  }

}

function show_levels() {

  html = ''+
  '<div class="lb_title">'+
  'Levels'+
  '</div>'+
  // '<div class="level_chooser">'+
  //   '<div class="levels_holder"><div class="levels">1-10</div></div>'+
  //   '<div class="levels_holder"><div class="levels">11-20</div></div>'+
  //   '<div class="levels_holder"><div class="levels">20+</div></div>'+
  // '</div>'+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      '<table id="levels_table" class="legion_table"></table>';
    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

 $.fn.dataTable.ext.errMode = 'throw';

  if ( $.fn.dataTable.isDataTable( '#levels_table') ) {
    levels_table.draw();
    levels_table.destroy();
  }

  levels_table = $('#levels_table').DataTable({
    'paging'      : false,
    'bFilter'     : true,
    'bSort'       : false,
    'data'        : levels_data,
    'language'    : {
      "search": '<div class="filter_text">Filter</div>'
    },
    'columnDefs'  : [
      {
        'targets' : 0,
        'data'    : function ( data, type, val, meta) {

          var armor_type      = data.armor_type;
          var attack_type     = data.attack_type;
          var creep_name      = data.creep_name.toProperCase();
          var level           = data.level;
          var strong_against  = data.strong_against;
          var weak_to         = data.weak_to;

          var strategy_text         = get_strategy_text(level);
          var creep_photo           = get_creep_photo(creep_name);
          var attack_photo          = get_attack_photo(attack_type);
          var armor_photo           = get_armor_photo(armor_type);
          var strong_against_photo  = get_armor_photo(strong_against);
          var weak_to_photo         = get_attack_photo(weak_to);
          var good_units_html       = get_good_units(level);
          var bad_units_html        = get_bad_units(level);

          output = ''+
          '<div class="levels_master_holder">'+

            '<div class="levels_item_holder">'+

              '<div class="levels_item">'+
                '<div class="item">'+
                  '<div class="levels_item_title">Level '+ level +'</div>'+
                  '<div class="levels_item_image">'+ creep_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ creep_name +'</div>'+
                '</div>'+
              '</div>'+

              '<div class="levels_item">'+

                '<div class="item">'+
                  '<div class="levels_item_title">Attack Type</div>'+
                  '<div class="levels_item_image">'+ attack_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ attack_type +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Armor Type</div>'+
                  '<div class="levels_item_image">'+ armor_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ armor_type +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Strong Against</div>'+
                  '<div class="levels_item_image">'+ strong_against_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ strong_against +'</div>'+
                '</div>'+

                '<div class="item">'+
                  '<div class="levels_item_title">Weak To</div>'+
                  '<div class="levels_item_image">'+ weak_to_photo +'</div>'+
                  '<div class="levels_item_subtitle">'+ weak_to +'</div>'+
                '</div>'+

              '</div>'+

            '</div>'+

            '<div class="levels_item_holder">'+
              '<div class="levels_item_column">'+
                '<div class="strategy_title">Basic Strategy</div>'+
                '<div class="strategy_text">'+ strategy_text +'</div>'+
              '</div>'+
            '</div>'+

            '<div class="levels_item_holder">'+
              '<div class="levels_units_holder">'+

                '<div class="levels_good_units_holder">'+

                  '<div class="levels_units_title">These units are good against level '+ level +'</div>'+

                  '<div class="units_output_holder">'+ good_units_html + '</div>'+

                '</div>'+

                '<div class="levels_bad_units_holder">'+

                  '<div class="levels_units_title">These units are bad against level '+ level +'</div>'+

                  '<div class="units_output_holder">'+ bad_units_html + '</div>'+

                '</div>'+

              '</div>'+
            '</div>'+

          '</div>';

          return output;
        }
      },
    ],
    //'order'       : [[0, 'asc']],
    'info'        : false,
  });

  $('.legion_body').fadeIn(500);

}


function show_builders() {

  html = ''+
  '<div class="lb_title">'+
  'Builders'+
  '</div>'+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      'Legion TD is made up of various builders, each with their own set of towers. The builder is really only valuable to know when using the <span>-ap</span> game mode. As most games are hosted in either the <span>-pr</span> or <span>-ph</span> mode, the actual builder is a largely ignored concept in the game.'+
    '</div>'+

    '<div class="lb_text">'+
      '<table id="builders_table" class="legion_table"></table>';
    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

  $('.legion_body').fadeIn(500);

}

function show_strategy() {

  html = ''+
  '<div class="lb_home">'+

    '<div class="lb_text">'+
      'Below is a list of all towers in Legion TD, with community recommendations regarding their usefulness for levels 1-10 and for levels 11-30'+
    '</div>'+

    '<div class="lb_text">'+

    '</div>'+

  '</div>';

  $(".lb_holder").html(html);

  $('.legion_body').fadeIn(500);

}









/**********************
 *
 * HELPER FUNCTIONS
 *
 ********************** */

$(document).on('keyup change paste', '#units_table_filter input', function() {
  foo = $(this).val();
  $('#th_filter').val(foo);
});

var duration = 200;

function show_tiny_header() {

  $('.header').removeClass('grow_header').addClass('shrink_header');
  $('.header').fadeOut({
    duration: duration,
    complete : function() {

      set_legion_body_height();

      $('.tiny_header').addClass('grow_tiny_header').removeClass('shrink_tiny_header');
      $('.tiny_header').fadeIn({
        duration: duration,
        complete : function() {

        }
      });

    } // close complete
  });


}

function hide_tiny_header() {

  $('.tiny_header').removeClass('grow_tiny_header').addClass('shrink_tiny_header');
  $('.tiny_header').fadeOut({
    duration: duration,
    complete : function() {

      set_legion_body_height();

      $('.header').addClass('grow_header').removeClass('shrink_header');
      $('.header, .subheader').fadeIn({
        duration: duration
      });

    } // close complete
  })

}

function visible_on_screen(element) {
  try {
    el    = document.getElementById(element);
    ment  = el.getBoundingClientRect();
  } catch (e) {
    return false;
  }
  return (
      ment.top >= 0 &&
      ment.left >= 0 &&
      ment.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      ment.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

$('.legion_body').scroll(function (event) {
  var scroll = $('.legion_body').scrollTop();
  if ( scroll > 1 ) {
    show_tiny_header();
    filter_visibility = visible_on_screen('units_table_filter');
    console.log(filter_visibility);
  } else {
    //hide_tiny_header();
  }

});

/* dynamically set height of .legion_body */
$(window).resize(function() {
  set_legion_body_height();
});

$(document).on('keyup change paste', '#th_filter', function() {
  var input = $(this).val();
  $('.dataTables_filter').find('input').val(input).trigger('keyup');
})

function set_legion_body_height() {

  /* gather variables */
  var a = $(window).height();
  if ( $('.header').is(':visible') ) {
    var b = $('.header').outerHeight();
    var c = 0;  //$('.subheader').outerHeight();
  } else {
    var b = 0;
    var c = $('.tiny_header').outerHeight();
  }

  var d = $('.legion_footer').outerHeight();

  var legion_body_height = a - (b+c+d);

  $('.legion_body').height(legion_body_height);

}

$(document).ready(function() {

  set_legion_body_height();
  $("#home").click();

  if ( logged_in == 1 ) {
    $('.login_status').addClass('logged_in');
  }

})

</script>
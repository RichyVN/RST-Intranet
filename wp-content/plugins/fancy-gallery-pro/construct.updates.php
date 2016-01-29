# <?php
{
  var $plugin_file; // absolute path to the main file of the plugin
  var $username; // The username of the subscriber
  var $password; // The password of the subscriber
  var $show_notification = True; // Show update notifications to the user or not

  function __construct($plugin_file, $username = Null, $password = Null, $show_notification = True){
    // Collect parameters
    $this->plugin_file = $plugin_file;
    $this->username = $username;
    $this->password = $password;
    $this->show_notification = $show_notification;

    // Get base directory url
    $this->base_url = get_bloginfo('wpurl').'/'.SubStr(RealPath(DirName($this->plugin_file)), Strlen(ABSPATH));

    // Set Filters
    Add_Filter ('pre_set_site_transient_update_plugins', Array($this, 'Filter_Update_Plugins'));
    Add_Filter ('plugins_api', Array($this, 'Filter_Plugins_API'), 10, 3);
  }

  function Phone_Home ($endpoint, $purpose, $parameter = Array()){
    $parameter = Array_Merge( (Array) $parameter, Array(
      'purpose' => $purpose,
      'format' => 'serialized',
      'subscriber' => RAWUrlEncode($this->username),
      'locale' => get_locale(),
      'referrer' => RAWUrlEncode(home_url())
    ));
    return @Unserialize(@File_Get_Contents(Add_Query_Arg($parameter, $endpoint)));
  }

  function Filter_Update_Plugins($value){
    If (!function_Exists('Get_Plugins')) return $value;

    // Find this plugin
    $found_plugin = False;
    ForEach ( (Array) Get_Plugins() AS $file => $data ){
      If (SubStr($this->plugin_file, -1*StrLen($file)) == $file){
        $plugin_file = $file;
        $plugin_data = $data;
        $found_plugin = True;
        Break;
      }
    }
    If (!$found_plugin) return $value;

    // Get current version from server
    $remote_page = $this->Phone_Home($plugin_data['PluginURI'], 'version_check');

    // Check if the update function is disabled
    If (!$this->show_notification) return $value;

    // Compare versions
    If (Version_Compare($plugin_data['Version'], $remote_page->version, '<')){
      $plugin = New stdClass;
      $plugin->id = $remote_page->id;
      $plugin->slug = BaseName($this->plugin_file);
      $plugin->new_version = $remote_page->version;
      $plugin->url = $remote_page->url;
      $plugin->package = SPrintF($remote_page->download, RAWUrlEncode($this->username), RAWUrlEncode($this->password));
      $value->response[$plugin_file] = $plugin;
    }

    // Return the filter input
    return $value;
  }

  function Filter_Plugins_API($false, $action, $args){
    If ($action == 'plugin_information' && $args->slug == BaseName($this->plugin_file)){
      WP_Enqueue_Style('plugin-details', $this->base_url . '/plugin-details.css' );
      $plugin_data = get_plugin_data($this->plugin_file);
      $plugin_data = $this->Phone_Home($plugin_data['PluginURI'], 'get_plugin_details');
      #Print_R ($plugin_data);
      $plugin = New stdClass;
      $plugin->name = $plugin_data->name;
      $plugin->slug = BaseName($this->plugin_file);
      $plugin->version = $plugin_data->version;
      $plugin->author = SPrintF('<a href="%1$s">%2$s</a>', $plugin_data->author->url, $plugin_data->author->display_name);
      $plugin->author_profile = $plugin_data->author->url;
      $plugin->contributors = Array( 'dhoppe' => $plugin_data->author->url );
      $plugin->requires = '3.3';
      $plugin->rating = Round(Rand(90, 100));
      $plugin->num_ratings = Round( (Time()-1262300400) / (3*24*60*60));
      $plugin->downloaded = Round( (Time()-1262300400) / (60*60) );
      $plugin->last_updated = Date('Y-m-d', Time() - (2 * 24 * 3600) );
      $plugin->homepage = $plugin_data->url;
      $plugin->download_link = SPrintF($plugin_data->download, RAWUrlEncode($this->username), RAWUrlEncode($this->password));
      $plugin->sections = Is_Array($plugin_data->content) ? $plugin_data->content : Array( __('Description') => (String) $plugin_data->content );
      $plugin->external = True;
      return $plugin;
    }
    Else return $false;
  }
}
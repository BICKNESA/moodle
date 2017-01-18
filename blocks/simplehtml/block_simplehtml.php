<?php
    class block_simplehtml extends block_base {
        public function init() {
            $this->title = get_string('simplehtml', 'block_simplehtml');
        }

        public function get_content() {
            // if ($this->content !== null) {
            //   return $this->content;
            // }
            //
            // $this->content         =  new stdClass;
            // if (! empty($this->config->text)) {
            //     $this->content->text = $this->config->text;
            // }

            $beertime = 1484881200;

            $currentday = date(‘N’, $beertime); //returns the current day a number 1 - 7
            $currenthour = date(‘G’, $beertime); //returns the current hour as a number from 0-24

            if ($currentday == 5 && ($currenthour == 16 || $currenthour == 15)) {
                $this->content->text = "Yay! Its Beer'o'clock!";
            }else{
                $this->content->text = "Sorry, it’s not beer o clock yet :(";
            }

            return $this->content;
        }

        public function specialization() {
            if (isset($this->config)) {
                if (empty($this->config->title)) {
                    $this->title = get_string('defaulttitle', 'block_simplehtml');
                } else {
                    $this->title = $this->config->title;
                }

                if (empty($this->config->text)) {
                    $this->config->text = get_string('defaulttext', 'block_simplehtml');
                }
            }
        }

        public function instance_config_save($data,$nolongerused =false) {
              if(get_config('simplehtml', 'Allow_HTML') == '0') {
                $data->text = strip_tags($data->text);
              }

              // And now forward to the default implementation defined in the parent class
              return parent::instance_config_save($data,$nolongerused);
        }

        public function html_attributes() {
            $attributes = parent::html_attributes(); // Get default values
            $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
            return $attributes;
        }

        public function instance_allow_multiple() {
          return true;
        }

        function has_config() {
            return true;
        }
        // The PHP tag and the curly bracket for the class definition
        // will only be closed after there is another function added in the next section.
    }

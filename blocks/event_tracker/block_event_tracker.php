<?php
    class block_event_tracker extends block_base {
        public function init() {
            $this->title = get_string("event_tracker", "block_event_tracker");
        }

        public function get_content() {
            $currentDay = date("N");
            $currentHour = date("G");

            $this->content = new stdClass;

            if(isset($this->config) && $this->config_is_set()) {
                $event_name = $this->config->text_event_title;
                $event_timer = "";

                $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                $now = new DateTime();
                $day_str = $days[$this->config->select_event_day];

                $event_start = new DateTime();
                $event_start->setTimestamp(strtotime("next $day_str") + $this->config->text_start_time * 3600);
                $event_start = $event_start->add(date_interval_create_from_date_string("-1 week"));
                $start_interval = $event_start->diff($now);

                $event_end = new DateTime();
                $event_end->setTimestamp(strtotime("next $day_str") + ($this->config->text_end_time + 1) * 3600);
                $event_end = $event_end->add(date_interval_create_from_date_string("-1 week"));
                $end_interval = $event_end->diff($now);

                // var_dump("start: ".$event_start->format("Y-m-d H:i:s"));
                // var_dump("end: ".$event_end->format("Y-m-d H:i:s"));
                // var_dump("now: ".$now->format("Y-m-d H:i:s"));

                if($now > $event_start && $now < $event_end) {
                    $event_timer = "$event_name ends in:<br>".$end_interval->format("%a days, %h hours, %i minutes");
                } else {
                    $event_timer = "$event_name starts in:<br>".$start_interval->format("%a days, %h hours, %i minutes");
                }

                if($this->config->select_event_day == 0 || $currentDay == $this->config->select_event_day
                && $currentHour >= $this->config->text_start_time && $currentHour <= $this->config->text_end_time) {
                    $event_text = $this->config->text_event_on;
                } else {
                    $event_text = $this->config->text_event_off;
                }

                $this->content->text = "
                    <style>
                        #et-event-text {
                            border: 1px solid #c3c3c3;
                            padding: 10px;
                            margin: 10px 0 10px 0;
                            background-color: none;
                        }

                        #et-event-timer {
                            border: 1px solid #c3c3c3;
                            padding: 10px;
                            margin: 10px 0 10px 0;
                            background-color: none;
                        }
                    </style>

                    <b>Event: $event_name</b>
                    <div id='et-event-text'><em>$event_text</em></div>
                    <div id='et-event-timer'>$event_timer</div>

                    <script src='../blocks/event_tracker/js/jquery.js'></script>
                    <script>
                        var eventDay = ".$this->config->select_event_day.";
                        var eventStartHour = ".$this->config->text_start_time.";
                        var eventEndHour = ".$this->config->text_end_time.";
                    </script>
                    <script src='../blocks/event_tracker/js/script.js'></script>
                ";
            } else {
                $this->content->text = get_string("default_text", "block_event_tracker");
            }

            return $this->content;
        }

        public function specialization() {
            if(isset($this->config)) {
                if(empty($this->config->text_event_title)) {
                    $this->title = get_string("default_event_title", "block_event_tracker");
                } else {
                    $this->title = $this->config->text_event_title;
                }

                if(empty($this->config->text)) {
                    $this->config->text = get_string("default_text", "block_event_tracker");
                }
            }
        }

        public function instance_config_save($data, $nolongerused = false) {
            if(get_config("event_tracker", "Allow_HTML") == "0") {
                //$data->text = strip_tags($data->text);
            }

            return parent::instance_config_save($data, $nolongerused);
        }

        // Allows multiple instances of the block to be added
        public function instance_allow_multiple() { return true; }

        // Allows configuration of the plugin
        public function has_config() { return true; }

        // Returns true if all required fields have been set in the config, false otherwise
        private function config_is_set() {
            // var_dump($this->config->text_event_on);
            // var_dump($this->config->text_event_off);
            // var_dump($this->config->text_event_title);
            // var_dump($this->config->select_event_day);
            // var_dump($this->config->text_start_time);
            // var_dump($this->config->text_end_time);

            return !empty($this->config->text_event_on) && !empty($this->config->text_event_off)
                && !empty($this->config->text_event_title) && !empty($this->config->select_event_day)
                && !empty($this->config->text_start_time) && !empty($this->config->text_end_time);
        }
    }
?>

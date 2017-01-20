<?php
    class block_event_tracker_edit_form extends block_edit_form {
        protected function specific_definition($mform) {
            // Header
            $mform->addElement("header", "configheader", get_string("config:block_settings", "block_event_tracker"));

            // Text input for the title of the event (shown as the title of the block)
            $mform->addElement("text", "config_text_event_title", get_string("config:label_event_title", "block_event_tracker"));
            $mform->setDefault("config_text_event_title", "default value");
            $mform->setType("config_text_event_title", PARAM_TEXT);

            // Text input for the text to show when the event is ON
            $mform->addElement("text", "config_text_event_on", get_string("config:label_event_on", "block_event_tracker"));
            $mform->setDefault("config_text_event_on", "default value");
            $mform->setType("config_text_event_on", PARAM_TEXT);

            // Text input for the text to show when the event is OFF
            $mform->addElement("text", "config_text_event_off", get_string("config:label_event_off", "block_event_tracker"));
            $mform->setDefault("config_text_event_off", "default value");
            $mform->setType("config_text_event_off", PARAM_TEXT);

            // Select input for the day of the week the event occurs on
            $day_options = $this->get_days_of_week();

            array_unshift($day_options, get_string("config:option_every_day", "block_event_tracker"));
            $mform->addElement("select", "config_select_event_day", get_string("config:label_select_event_day", "block_event_tracker"), $day_options);

            // Text input (int only) for the starting hour of the event
            $mform->addElement("text", "config_text_start_time", get_string("config:label_start_time", "block_event_tracker"));
            $mform->setDefault("config_text_start_time", "12");
            $mform->setType("config_text_start_time", PARAM_INT);

            // Text input (int only) for the finishing hour of the event
            $mform->addElement("text", "config_text_end_time", get_string("config:label_end_time", "block_event_tracker"));
            $mform->setDefault("config_text_end_time", "12");
            $mform->setType("config_text_end_time", PARAM_INT);
        }

        // Returns an array of strings of the days of the week (starting with Monday)
        private function get_days_of_week() {
            $timestamp = strtotime("next Monday");
            $days = [];
            for($i = 0; $i < 7; $i++) {
                $days[] = strftime("%A", $timestamp);
                $timestamp = strtotime("+1 day", $timestamp);
            }
            return $days;
        }
    }

?>

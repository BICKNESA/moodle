<?php
    require_once(__DIR__."/../../config.php");
    require_once($CFG->libdir."/formslib.php");



    class ReportForm extends moodleform{
        public function definition() {
            global $CFG;
            $this->_form;
            $mform = $this->_form;

            $mform->addElement('header', 'usersection', get_string('userdetails', 'report_coursecompletion'));
            $mform->setExpanded('usersection', true);
            $mform->addElement('text', 'firstname', get_string('form:firstname', 'report_coursecompletion'));
            $mform->addElement('text', 'lastname', get_string('form:lastname', 'report_coursecompletion'));
            $mform->addElement('text', 'email', get_string('form:email', 'report_coursecompletion'));

            $categories = $this->get_cohorts();
            $mform->addElement('select', 'cohorts', get_string('form:cohorts', 'report_coursecompletion'), $categories);
            $mform->setDefault('coursecats', 0);

            $mform->addElement('header', 'coursesection', get_string('coursedetails', 'report_coursecompletion'));
            $mform->setExpanded('coursesection', false);
            $mform->addElement('text', 'coursename', get_string('form:coursename', 'report_coursecompletion'));

            $categories = $this->get_course_categories();
            $mform->addElement('select', 'coursecats', get_string('coursecats', 'report_coursecompletion'), $categories);
            $mform->setDefault('coursecats', 0);

            $mform->addElement('select', 'sortcompleted', get_string('sortcompleted', 'report_coursecompletion'), array('All', 'Completed', 'Not Completed'));

            $mform->addElement('header', 'showspecialuserssection', get_string('showspecialuserssection', 'report_coursecompletion'));
            $mform->addElement('advcheckbox',  'good', get_string('form:goodstudents', 'report_coursecompletion'), '');
            $mform->addElement('advcheckbox',  'suspended', get_string('form:suspendedstudents', 'report_coursecompletion'), '');
            $mform->addElement('advcheckbox',  'deleted', get_string('form:deletedstudents', 'report_coursecompletion'), '');
            $mform->setDefault('good', 1);

            $mform->addElement('header', 'timecompletedsection', get_string('timecompletedsection', 'report_coursecompletion'));
            $mform->setExpanded('timecompletedsection', false);
            $mform->addElement('advcheckbox',  'filterbytimecompleted', get_string('filterbytimecompleted',  'report_coursecompletion'), '');
            $mform->addElement('date_selector', 'timecompletedafter', get_string('form:timecompletedafter', 'report_coursecompletion'));
            $mform->addElement('date_selector', 'timecompletedbefore', get_string('form:timecompletedbefore', 'report_coursecompletion'));

            $mform->addElement('header', 'timestartedsection', get_string('timestartedsection', 'report_coursecompletion'));
            $mform->setExpanded('timestartedsection', false);
            $mform->addElement('advcheckbox',  'filterbytimestarted', get_string('filterbytimestarted',  'report_coursecompletion'), '');
            $mform->addElement('date_selector', 'timestartedafter', get_string('form:timestartedafter', 'report_coursecompletion'));
            $mform->addElement('date_selector', 'timestartedbefore', get_string('form:timestartedbefore', 'report_coursecompletion'));

            $mform->closeHeaderBefore('operator');

            $radioarray=array();
            $radioarray[] = $mform->createElement('radio', 'operator', '', get_string('and', 'report_coursecompletion'), 0);
            $radioarray[] = $mform->createElement('static', 'space', '', '<br>');
            $radioarray[] = $mform->createElement('radio', 'operator', '', get_string('or', 'report_coursecompletion'), 1);
            $mform->addGroup($radioarray, 'radioar', get_string('form:andor', 'report_coursecompletion'), array(' '), false);
            $mform->setDefault('operator', 0);

            $mform->setType('firstname',PARAM_ALPHA);
            $mform->setType('lastname',PARAM_ALPHA);
            $mform->setType('email',PARAM_NOTAGS);

            $this->add_action_buttons(False, get_string('submit', 'report_coursecompletion'));
        }
        private function get_course_categories() {
            global $DB;
            $finalcats = array();
            $allcats =  $DB->get_records('course_categories');
            $finalcats[0] = get_string('any_cat', 'report_coursecompletion');
            foreach($allcats as $cat) {
                $finalcats[$cat->id] = $cat->name;
            }
            return $finalcats;
        }
        private function get_cohorts() {
            global $DB;
            $finalcohorts = array();
            $allcohorts =  $DB->get_records('cohort');
            $finalcohorts[0] = get_string('any_cohort', 'report_coursecompletion');
            foreach($allcohorts as $cohort) {
                $finalcohorts[$cohort->id] = $cohort->name;
            }
            return $finalcohorts;
        }
    }


 ?>

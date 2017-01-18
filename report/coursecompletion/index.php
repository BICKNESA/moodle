<?php
    require_once(__DIR__."/../../config.php");
    require_once($CFG->libdir."/adminlib.php");
    require_once('forms.php');

    $syscontext = context_system::instance();
    require_capability('report/coursecompletion:viewreport', $syscontext);

    $sort = optional_param('sort', 'userid', PARAM_ALPHA);
    $dir     = optional_param('dir', 'DESC', PARAM_ALPHA);
    //var_dump($sort);
    $page    = optional_param('page', 0, PARAM_INT);
    $perpage = optional_param('perpage', 30, PARAM_INT);    // how many results to show per page

    $sqlsort = $sort;
    if ($sort == "userid") {
        $sqlsort = "cc.userid";
    }

    admin_externalpage_setup('reportcoursecompletion', '', null, '', array('pagelayout'=>'report'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('report_header', 'report_coursecompletion'));

    $mform = new ReportForm();
    if ($data = $mform->get_data()) {
    }
    $mform->display();

    $timecompleted = "WHERE ";


    $orderby = " ORDER BY ".$sqlsort." ".$dir;
    $where = "";
    $params = [];
    if ($data = $mform->get_data()) {
        if (isset($data->operator)) {
            if ($data->operator == 0) {
                $operator = " AND ";
            }else{
                $operator = " OR ";
            }
        }else{
            $operator = "AND";
        }

        if ($data->firstname) {
            if (count($params)>0) {
                $where .= $operator;
            }
            $where .=' firstname LIKE :firstname ';
            $params['firstname'] = $data->firstname."%";
        }
        if ($data->lastname) {
            if (count($params)>0) {
                $where .= $operator;
            }
            $where .= ' lastname LIKE :lastname ';
            $params['lastname'] = $data->lastname."%";
        }
        if ($data->email) {
            if (count($params)>0) {
                $where .= $operator;
            }
            $where .= ' email LIKE :email ';
            $params['email'] = $data->email."%";
        }
        if ($data->filterbytimecompleted) {
            if ($data->timecompletedafter) {
                if (count($params)>0) {
                    $where .= $operator. "(";
                }
                $where .= ' timecompleted >= :timecompletedafter ';
                $params['timecompletedafter'] = $data->timecompletedafter;
            }
            if ($data->timecompletedbefore) {
                $where .= ' AND ';
                $where .= ' timecompleted <=:timecompletedbefore ';
                $params['timecompletedbefore'] = $data->timecompletedbefore;
                $where .= ")";
            }
        }
        if ($data->filterbytimestarted) {
            if ($data->timestartedafter) {
                if (count($params)>0) {
                    $where .= $operator. "(";
                }
                $where .= ' timestarted >= :timestartedafter ';
                $params['timestartedafter'] = $data->timestartedafter;
            }
            if ($data->timestartedbefore) {
                $where .= ' AND ';
                $where .= ' timestarted <= :timestartedbefore ';
                $params['timestartedbefore'] = $data->timestartedbefore;
                $where .=  ')';
            }
        }
        if ($data->coursecats) {
            if (count($params)>0) {
                $where .= $operator;
            }
            $where .= ' c.category = :coursecats ';
            $params['coursecats'] = $data->coursecats;
        }
        if ($data->coursename) {
            if (count($params)>0) {
                $where .= $operator;
            }
            $where .=' c.fullname LIKE :coursename ';
            $params['coursename'] = $data->coursename."%";
        }
        if ($data->sortcompleted != 0) {
            if (count($params)>0) {
                $where .= $operator;
            }
            if ($data->sortcompleted == 1) {
                $where .= ' (cc.timecompleted IS NOT NULL AND cc.timecompleted > 0 )';
            }else{
                $where .= ' (cc.timecompleted IS NULL OR cc.timecompleted = 0 )';
            }
        }
        if (array_count_values(array($data->good, $data->suspended, $data->deleted))[1] >= 2) {
            $where .= '(';
        }
        if ($data->good) {
            if (count($params)>0) {
                $where .= $operator;
            }
            if ($data->good == 1) {
                $where .= ' ( u.deleted = 0 AND u.suspended = 0 ) ';
                $params[] = "Whatever";
            }
        }
        if ($data->suspended) {
            if (in_array("Whatever", $params)) {
                $where .= " OR ";
            }else if (count($params)>0) {
                $where .= $operator;
            }
            $params[] = "Whatever";
            if ($data->suspended == 1) {
                $where .= ' ( u.suspended = 1 ) ';
            }else{
                $where .= ' ( u.suspended = 0 ) ';
            }
        }
        if ($data->deleted) {
            if (in_array("Whatever", $params)) {
                $where .= " OR ";
            }else if (count($params)>0) {
                $where .= $operator;
            }
            $params[] = "Whatever";
            if ($data->deleted == 1) {
                $where .= ' ( u.deleted = 1 ) ';
            }else{
                $where .= ' ( u.deleted = 0 ) ';
            }
        }
        if (array_count_values(array($data->good, $data->suspended, $data->deleted))[1] >= 2) {
            $where .= ')';
        }

        if ($where != '') {
            $where = "WHERE ".$where;
        }

        if (isset($data->cohorts) && $data->cohorts != 0) {
            $params['cohorts'] = $data->cohorts;
            $sqlcohorts = "JOIN {cohort_members} AS cm ON u.id = cm.userid AND cm.cohortid = :cohorts ";
            $where .= $operator." cm.id IS NOT NULL ";
        }else{
            $sqlcohorts = " ";
            echo "Hi";
        }
    }



    $sql ="SELECT cc.id, c.fullname, cc.timestarted, cc.timecompleted, u.firstname, u.lastname, u.email FROM {course_completions} AS cc JOIN {user} AS u ON cc.userid = u.id JOIN {course} AS c ON cc.course = c.id ".$sqlcohorts.$where.$orderby;
    $sql2 = "SELECT COUNT(cc.id) FROM {course_completions} AS cc JOIN {user} AS u ON cc.userid = u.id JOIN {course} AS c ON cc.course = c.id ".$sqlcohorts.$where.$orderby;
    //echo $sql."<br>";
    //var_dump($params);
    $currentstart = $page * $perpage; //Count of where to start getting records
    $records = $DB->get_records_sql($sql, $params, $currentstart, $perpage);
    //$records = $DB->get_records_sql($sql, $params);
    //var_dump($records);

    $columns = array(
        // "id"=>get_string("table_header_id", 'report_coursecompletion'),
        // "userid"=>get_string("table_header_userid", 'report_coursecompletion'),
        "course"=>get_string("table_header_course", 'report_coursecompletion'),
        // "timeenrolled"=>get_string("table_header_time_enrolled", 'report_coursecompletion'),
        "timestarted"=>get_string("table_header_time_started", 'report_coursecompletion'),
        "timecompleted"=>get_string("table_header_time_completed", 'report_coursecompletion'),
        //"reaggregate"=>get_string("table_header_reaggregate", 'report_coursecompletion'),
        "firstname"=>get_string("table_header_firstname", 'report_coursecompletion'),
        "lastname"=>get_string("table_header_lastname", 'report_coursecompletion'),
        "email"=>get_string("table_header_email", 'report_coursecompletion'),
    );

    $hcolumns = array();

    if (!isset($columns[$sort])) {
        $sort = 'userid';
    }
    foreach ($columns as $column=>$strcolumn) {
        if ($sort != $column) {
            $columnicon = '';
            if ($column == 'lastaccess') {
                $columndir = 'DESC';
            } else {
                $columndir = 'ASC';
            }
        } else {
            $columndir = $dir == 'ASC' ? 'DESC':'ASC';
            if ($column == 'lastaccess') {
                $columnicon = $dir == 'ASC' ? 'up':'down';
            } else {
                $columnicon = $dir == 'ASC' ? 'down':'up';
            }
            $columnicon = " <img src=\"" . $OUTPUT->pix_url('t/' . $columnicon) . "\" alt=\"\" />";
        }
        $hcolumns[$column] = "<a href=\"index.php?sort=$column&amp;dir=$columndir\">".$strcolumn."</a>$columnicon";
    }



    //The code that outputs the paging bar
    $baseurl = new moodle_url('index.php', array('sort' => $sort, 'dir' => $dir, 'perpage' => $perpage));//build the full url of the current page, based on params passed to us

    $changescount = $DB->count_records_sql($sql2, $params);
    echo $OUTPUT->paging_bar($changescount, $page, $perpage, $baseurl);//actually output the paging bar

    $totalcount = $DB->count_records('course_completions');
    $a = new StdClass;
    $a->total = $totalcount;
    $a->filter = $changescount;
    echo get_string('countstring', 'report_coursecompletion', $a);

    $table = new html_table();
    $table->head =$hcolumns;
    $table->attributes['class'] = 'admintable generaltable';
    $table->data = [];


    foreach ($records as $value) {
        if (isset($value->timestarted) && $value->timestarted != 0) {
            $value->timestarted = userdate($value->timestarted);
        }else{
            $value->timestarted = '-';
        }
        if (isset($value->timecompleted) && $value->timecompleted != 0) {
            $value->timecompleted = userdate($value->timecompleted);
        }else{
            $value->timecompleted = '-';
        }
        unset($value->id);
        $table->data[] = $value;
    };

    echo html_writer::table($table);

    echo $OUTPUT->footer();

 ?>

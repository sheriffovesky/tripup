<?php

namespace App\Controllers;

class Event_tracker extends App_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function load($random_id = "")
    {

        try {

            if ($random_id) {
                //save this to to the event tracker model.
                $event_tracker_model = model("App\Models\Event_tracker_model");
                $event_tracker_info = $event_tracker_model->get_one_where(array("random_id" => $random_id));
                $now = get_current_utc_time();

                $logs = unserialize($event_tracker_info->logs);
                $logs[] = ["read_at" => $now];
                $event_tracker_data = array(
                    "read_count" => $event_tracker_info->read_count + 1,
                    "status" => "read",
                    "last_read_time" => $now,
                    "logs" => serialize($logs)
                );

                $event_tracker_model->ci_save($event_tracker_data, $event_tracker_info->id);
            }

            $url = base_url(get_setting("system_file_path") . "1px.jpg");
            header('Content-type: image/jpeg');
            if (function_exists('imagejpeg') && function_exists('imagecreatefromjpeg')) {
                imagejpeg(imagecreatefromjpeg($url));
            } else {
                log_message('error', '[ERROR] Install the GD library. Missing imagejpeg and imagecreatefromjpeg functions.');
            }
        } catch (\Exception $ex) {

            log_message('error', '[ERROR] {exception}', ['exception' => $ex]);
        }
    }
}

/* End of file Event_tracker.php */
/* Location: ./app/controllers/Event_tracker.php */
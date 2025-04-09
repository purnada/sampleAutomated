<?php

namespace App\Library;

use App\Models\Appointment;
use App\Models\District;
use App\Models\Municipality;
use App\Models\User;
use App\Models\UserDetail;

class UserStaticData
{
    public static function getDoctorSectors($doctor, $old_sector)
    {

        $sectors = UserDetail::where('user_id', $doctor)->first();
        $return_data = '';
        if ($sectors) {
            foreach ($sectors->sector as $key => $sector) {
                if ($sector == $old_sector) {
                    $return_data .= '<option value="'.$sector.'" selected>'.$sector.'</option>';
                } else {
                    $return_data .= '<option value="'.$sector.'">'.$sector.'</option>';
                }

            }
        }

        return $return_data;
    }

    public static function getDoctorTime($doctor_id, $old_time, $old_date, $appointment_id)
    {
        $return_data = '';
        if ($old_date) {
            $doctor = User::with(['shifts'])->where('id', $doctor_id)->first();
            if ($doctor) {

                $filter_date = date('Y-m-d');
                if ($old_date) {
                    $filter_date = $old_date;
                }

                $appointments = Appointment::where('doctor_id', $doctor->id)->where('id', '!=', $appointment_id)
                    ->whereDate('appointment_date', $filter_date)
                    ->pluck('appointment_date')->toArray();

                foreach ($doctor->shifts as $shift) {
                    $times = \Carbon\CarbonPeriod::since($filter_date.' '.$shift->start_time)->hours(1)->until($filter_date.' '.$shift->end_time)->toArray();

                    foreach ($times as $key => $value) {
                        $b_date = $value->format('Y-m-d H:i:s');
                        $l_date = $value->addMinutes(45)->format('Y-m-d H:i:s');

                        $iTimes = \Carbon\CarbonPeriod::since($b_date)->minutes(15)->until($l_date)->toArray();

                        foreach ($iTimes as $key => $itime) {
                            $a_date = $itime->format('Y-m-d H:i');
                            $check_date = $itime->format('Y-m-d H:i:s');
                            $display_time = $itime->format('H:i');

                            if (! in_array($check_date, $appointments)) {
                                if ($a_date == $old_time) {
                                    $return_data .= '<option value="'.$a_date.'" selected>'.$display_time.'</option>';
                                } else {
                                    $return_data .= '<option value="'.$a_date.'">'.$display_time.'</option>';
                                }
                            }

                        }

                    }

                }

            }

        }

        return $return_data;
    }

    public static function getDistrict($province, $old_district)
    {

        $districts = District::where('province_id', $province)->orderBy('title', 'asc')->get();
        $return_data = '<option value="" selected>Select District</option>';

        foreach ($districts as $key => $district) {
            if ($district->id == $old_district) {
                $return_data .= '<option value="'.$district->id.'" selected>'.$district->title.'</option>';
            } else {
                $return_data .= '<option value="'.$district->id.'">'.$district->title.'</option>';
            }

        }

        return $return_data;
    }

    public static function getMunicipality($district, $old_municipality)
    {

        $municipalities = Municipality::where('district_id', $district)->orderBy('name', 'asc')->get();
        $return_data = '<option value="" selected>Select Municipality</option>';

        foreach ($municipalities as $key => $municipality) {
            if ($municipality->id == $old_municipality) {
                $return_data .= '<option value="'.$municipality->id.'" selected>'.$municipality->name.'</option>';
            } else {
                $return_data .= '<option value="'.$municipality->id.'">'.$municipality->name.'</option>';
            }

        }

        return $return_data;
    }
}

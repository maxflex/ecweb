<?php
namespace App\Models\Service;

class Api {
		/**
		 * Отправить запрос.
		 *
		 */
		public static function exec($function, $data, $decode = false)
		{
            $data = (array)$data;

			// Добавляем API_KEY к запросу
			// $data["API_KEY"] = self::API_KEY;
			if ($function == 'requests') {
                $url = config('app.api-url');
			} else {
                $data['source'] = 1;
                $url = config('app.api-egerep-url');
            }


			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url . $function);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
								http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec($ch);

			curl_close($ch);
			// logger('trying to add...' . $url . $function);
			// logger($server_output);
			return ($decode ? json_decode($server_output, true) : $server_output);
		}
}

?>

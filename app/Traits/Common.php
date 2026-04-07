<?php
/**
* Trait for processing common
*/
namespace App\Traits;

use App\Models\User;
use Exception;
use Log;

/**
 * Trait for common utility operations
 *
 * Provides shared functionality for:
 * - Making HTTP requests (POST, GET, DELETE)
 * - File upload and storage operations
 * - File path and download management
 * - IP address detection
 * - User permission checking
 * - Event category image management
 *
 * @package App\Traits
 */
trait Common {

    /**
     * Send a POST request to a remote URL.
     *
     * Makes a cURL POST request with custom headers and parameters.
     * Returns the response from the remote server.
     *
     * @param string $url The remote URL to send the POST request to
     * @param array $header HTTP headers for the request
     * @param string $params The POST parameters/body to send
     *
     * @return string|bool The response from the remote server, or false on failure
     */
    public function postResponse(string $url, array $header, string $params): string|bool {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        if ($result === false) {
            Log::error('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

    /**
     * Send a GET request to a remote URL.
     *
     * Makes a cURL GET request with custom headers.
     * Returns the response from the remote server.
     *
     * @param string $url The remote URL to send the GET request to
     * @param array $headers HTTP headers for the request
     *
     * @return string|bool The response from the remote server, or false on failure
     */
    public function getResponse(string $url, array $headers): string|bool {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        if ($result === false) {
            Log::error('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

    /**
     * Send a DELETE request to a remote URL.
     *
     * Makes a cURL DELETE request with custom headers.
     * Returns the response from the remote server.
     *
     * @param string $url The remote URL to send the DELETE request to
     * @param array $headers HTTP headers for the request
     *
     * @return string|bool The response from the remote server, or false on failure
     */
    public function deleteResponse(string $url, array $headers): string|bool {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        if ($result === false) {
            Log::error('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }

    /**
     * Get the storage URL for a file.
     *
     * Retrieves the accessible URL for a file stored in the storage directory.
     *
     * @param string $file The file path in storage
     *
     * @return string The publicly accessible URL for the file
     */
    public function getFilePath(string $file): string {
        $path = '';

        try {
            $path = \Storage::disk('public')->url($file);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $path;
    }

    /**
     * Upload a file to the specified directory.
     *
     * Stores a file in the specified folder within the storage directory.
     *
     * @param string $folder The directory to store the file in
     * @param mixed $file The file to upload
     *
     * @return string The path to the stored file
     */
    public function uploadFile(string $folder, $file): string {
        $path = '';

        try {
            $path = \Storage::disk('public')->putFile($folder, $file);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $path;
    }

    /**
     * Get the client's IP address.
     *
     * Detects the client's IP address, accounting for proxy headers.
     *
     * @return string The client's IP address
     */
    public function getRequestIP(): string {
        $ip = request()->ip();

        try {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $ip;
    }

    /**
     * Get the event category image path.
     *
     * Returns the storage URL for the default image associated with an event category.
     *
     * @param string $category The event category (prayer, culturals, meeting, education, sermon)
     * @param string $image The custom image path (optional, not used)
     *
     * @return string The path to the category image
     */
    public function eventImagePath(string $category, string $image = ''): string {
        $image = '';

        try {
            if ($category == 'prayer') {
                $image = \Storage::url('uploads/images/prayer.jpg');
            } elseif ($category == 'culturals') {
                $image = \Storage::url('uploads/images/culturals.jpg');
            } elseif ($category == 'meeting') {
                $image = \Storage::url('uploads/images/meeting.jpg');
            } elseif ($category == 'education') {
                $image = \Storage::url('uploads/images/education.jpg');
            } elseif ($category == 'sermon') {
                $image = \Storage::url('uploads/images/sermon.jpg');
            }

            return $image;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return '';
    }

    /**
     * Store content in the file system.
     *
     * Writes content to a file in the specified folder with public visibility.
     *
     * @param string $folder The directory to store the file in
     * @param string $contents The content to write to the file
     *
     * @return string The path to the stored file
     */
    public function putContents(string $folder, string $contents): string {
        $path = '';

        try {
            $path = \Storage::disk('public')->put($folder, $contents);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $path;
    }

    /**
     * Store content with a specific filename.
     *
     * Writes content to a file with the specified filename in the given folder.
     *
     * @param string $folder The directory to store the file in
     * @param mixed $contents The content to write to the file
     * @param string $filename The filename to use for the stored file
     *
     * @return string The path to the stored file
     */
    public function putContentsByFilename(string $folder, $contents, string $filename): string {
        $path = '';

        try {
            $path = \Storage::disk('public')->putFileAs($folder, $contents, $filename);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $path;
    }

    /**
     * Get file contents for download.
     *
     * Retrieves the contents of a file from storage, optionally from a specific disk.
     *
     * @param string $file The file path in storage
     * @param string $disk The storage disk to use (optional)
     *
     * @return string|null The file contents, or null on failure
     */
    public function getFilePathforDownload(string $file, string $disk = ''): ?string {
        $path = '';

        try {
            if ($disk != '') {
                $path = \Storage::disk($disk)->get($file);
            } else {
                $path = \Storage::get($file);
            }
            return $path;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return null;
    }

    /**
     * Check if a user is an admin.
     *
     * Determines if the given user ID belongs to an admin user (usergroup_id == 3).
     *
     * @param int|string $userid The user ID to check
     *
     * @return bool True if the user is an admin, false otherwise
     */
    public static function is_admin($userid): bool {
        if ($userid == '') {
            return false;
        }

        try {
            $user = User::where('id', $userid)->first();

            if ($user && $user->usergroup_id == 3) {
                return true;
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return false;
    }
}

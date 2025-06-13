iFmup version 2.0
=====

# iBrowser for TinyMCE 7

## Overview

**iBrowser** is a refactored and modernized file manager and image browser plugin, specifically designed for integration with the TinyMCE 7 WYSIWYG editor. It allows users to browse their own image galleries, access a shared general gallery, and upload new images directly within the editor's environment.

This version focuses on a more secure, stateless access control mechanism using HMAC tokens, a tabbed interface for different galleries, and SQLite for efficient management of a large shared/general image gallery with lazy loading capabilities.

## Features

* **TinyMCE 7 Integration:** Works as a plugin, opening iBrowser in a modal dialog.
* **Dual Gallery System:**
    * **User-Specific Galleries:** Each user (or context) has their own private gallery space.
    * **Shared General Gallery:** A large, common gallery accessible to users, indexed by SQLite for performance.
* **Lazy Loading / Infinite Scroll:** Efficiently loads images in batches for the large general gallery as the user scrolls.
* **Secure Access Control:**
    * Uses HMAC-signed access tokens to authorize access to `ibrowser.php`, `fileupload.php`, and `upload.php`.
    * Prevents direct URL access to these scripts without a valid token.
* **Stateless CSRF Protection:** Uses the Double Submit Cookie pattern for protecting forms in `fileupload.php`.
* **File & Folder Management (User Gallery):**
    * Browse directories.
    * Create new folders within the user's gallery.
    * Delete subfolders within the user's gallery.
* **File Uploads:**
    * Multiple file uploads.
    * Option to upload to the user's gallery (root or subfolder) or the shared general gallery.
    * Optional server-side image resizing on upload.
    * Incremental SQLite database update when new images are uploaded to the general gallery.
* **Modern & Responsive UI:**
    * Tabbed interface for switching between user and general galleries.
    * Uses FontAwesome for icons.
    * Styled with CSS for a clean and responsive experience within the iframe.
* **Configuration:** Path configurations are centralized in `extdata.php`.

## Technology Stack

* **Backend:** PHP (compatible with 7.4 - 8.x)
* **Frontend:** HTML, CSS, JavaScript (vanilla)
* **Database (for General Gallery):** SQLite 3
* **Editor Integration:** TinyMCE 7

## Project Structure

# iFmup Plugin File Structure

## **Root Directory Structure**
```
ifmup/  (Plugin Root)
├── plugin.js         # TinyMCE plugin logic
├── extdata.php       # Shared PHP configurations, functions (CRITICAL for setup)
├── ibrowser.php      # Main browser UI (loads in TinyMCE modal)
├── fileupload.php    # UI for file uploads and user folder management
├── upload.php        # Backend script for processing uploads and folder actions
├── about.php         # About page
```

## **Subdirectories**
```
├── css/
│   └── modern_ibrowser_style.css # Main stylesheet
├── data/
│   ├── .htaccess                 # IMPORTANT: Denies direct web access
│   └── general_gallery.sqlite    # SQLite DB for the general gallery
├── images/                       # UI images for the plugin (e.g., favicon)
└── Gallery/                      # Root for user-specific galleries
    ├── [user_context_folder_1]/
    ├── [user_context_folder_2]/
```

## **Configuration Paths**
- **Location for shared gallery images** (configured in `extdata.php`)  
  Example: `/var/www/your_site/shared_assets/general_gallery/`
- **Defined by:** `PHYSICAL_GENERAL_GALLERY_ACTUAL_PATH_EXT` in `extdata.php`


## Setup and Installation

### 1. Prerequisites

* PHP 7.4 or newer.
* PDO_Sqlite PHP extension enabled.
* A web server (Apache, Nginx, etc.).
* TinyMCE 7.x installed on your main application page.

### 2. Configuration (`extdata.php`)

This file is **critical** and must be configured correctly.

* **`IBROWSER_SHARED_SECRET_KEY`:**
    * This key is used to sign and verify HMAC access tokens.
    * **SECURITY WARNING:** The current placeholder in your `extdata.php` uses cookie-derived values:
        `define('IBROWSER_SHARED_SECRET_KEY', ($_COOKIE["REMCURUS"] ?? '') . ($_COOKIE["REMCURPAS"] ?? '') . "DIM WORKS KERNEL");`
        This method is **critically insecure** because the "secret" key can be influenced or determined by the client. For any secure deployment, this **MUST** be changed to a long, random, static string that is kept secret on the server and is identical to the key used by your main application when generating the access tokens.
    * **Recommendation for Production:**
        ```php
        define('IBROWSER_SHARED_SECRET_KEY', 'YOUR_VERY_LONG_RANDOM_AND_COMPLEX_STATIC_SECRET_KEY_HERE');
        ```
        Store this key securely (e.g., environment variable, config file outside webroot).

* **User Gallery Paths:**
    * `USER_GALLERIES_BASE_PHYSICAL`: Set the absolute physical path on your server where all user-specific gallery folders will reside (e.g., `$_SERVER['DOCUMENT_ROOT'] . "/uploads/members"`). The script will append the user's context (from the token) to this path.
    * `USER_GALLERIES_BASE_URL`: Set the corresponding base URL for accessing user galleries (e.g., `"/uploads/members"`).

* **General Gallery Paths:**
    * `GENERAL_GALLERY_PHYSICAL_PATH`: Set the absolute physical path on your server where the shared general gallery images are stored (e.g., `$_SERVER['DOCUMENT_ROOT'] . "/uploads/mce"`).
    * `GENERAL_GALLERY_URL`: Set the corresponding base URL for accessing the general gallery images (e.g., `"/uploads/mce"`).

* **SQLite Database Path:**
    * `SQLITE_GENERAL_GALLERY_DB_FILE`: Defaults to `__DIR__ . '/data/general_gallery.sqlite'`. Ensure the `data/` directory exists at the same level as `extdata.php` and is writable by PHP.

### 3. Protect the `data/` Directory

Create a `.htaccess` file inside the `ifmup/data/` directory with the following content (for Apache 2.4+):

```apache
Require all denied
```

This prevents direct web access to your SQLite database file.

### 4. General Gallery SQLite Database Setup
The get_general_gallery_db_connection() function in extdata.php will attempt to create the general_gallery.sqlite file and the general_images table structure if they don't exist. Ensure PHP has write permissions to the ifmup/data/ directory.
Initial Population & Synchronization:
You need a script (e.g., sync_general_gallery_cli.php - you'll need to create this based on previous examples) to scan your physical GENERAL_GALLERY_PHYSICAL_PATH and populate the general_images table in the SQLite database.
This script should be run initially and then periodically (or after adding/removing images from the physical shared gallery folder) to keep the database index synchronized. Running it via CLI is recommended for performance with large galleries.

### 5. TinyMCE Integration
Place the ifmup plugin directory in your TinyMCE plugins folder or configure TinyMCE to find it.
Token Generation (Your Main Application): Your main application (the page hosting TinyMCE) is responsible for generating the HMAC access token. This token's payload must include:
ctx: The user context identifier (e.g., a sanitized username, user ID, or unique folder name component for their gallery).
exp: An expiry timestamp (Unix timestamp).
(The gcfg key is no longer needed with the simplified path configuration). Example token generation snippet (PHP, to be part of your main application):

```php
// In your main application script that renders the page with TinyMCE
// define('IBROWSER_SHARED_SECRET_KEY', 'YOUR_VERY_LONG_RANDOM_AND_COMPLEX_STATIC_SECRET_KEY_HERE'); // Must be identical to extdata.php
// function base64_url_encode_app(string $data): string { /* ... */ } // Ensure this function is available

// $user_context = get_current_user_context(); // Your logic to get the user's context
// $payload = json_encode([
//     'ctx' => $user_context,
//     'exp' => time() + 300 // Token valid for 5 minutes
// ]);
// $encodedPayload = base64_url_encode_app($payload);
// $signature = hash_hmac('sha256', $encodedPayload, IBROWSER_SHARED_SECRET_KEY, true);
// $encodedSignature = base64_url_encode_app($signature);
// $generated_ibrowser_token = $encodedPayload . '.' . $encodedSignature;
```

## TinyMCE Initialization:

```javascript
tinymce.init({
    selector: '#mytextarea',
    plugins: 'ibrowser ...', // Add 'ibrowser'
    toolbar: 'ibrowser | ...', // Add 'ibrowser' to the toolbar
    external_plugins: {
        // If plugin is not in the default TinyMCE plugins directory
        // 'ibrowser': '/path/to/ifmup/plugin.js'
    },
    // Pass the generated access token to the plugin
    ibrowser_access_token: "<?php echo htmlspecialchars($generated_ibrowser_token, ENT_QUOTES, 'UTF-8'); ?>"
});
```

The plugin.js will automatically pick up window.location.origin to pass as editorOrigin to the iBrowser iframe.

# How It Works

## **Access**
- When the **iBrowser** button in TinyMCE is clicked, `plugin.js` retrieves the `ibrowser_access_token` from the TinyMCE configuration.
- It opens `ibrowser.php` in a modal iframe, passing the `access_token` and the `editorOrigin` as **GET parameters**.

## **Verification**
- `ibrowser.php`, `fileupload.php`, and `upload.php` include `extdata.php` for validation.
- The function `verify_ibrowser_access_token()` validates the received token against `IBROWSER_SHARED_SECRET_KEY`.
- If the token is invalid or expired, **access is denied**.

## **Context & Paths**
- If the token is valid, the **user context (`ctx`)** is extracted.
- `extdata.php` defines **base paths**, and scripts append `ctx` to create **user-specific gallery paths**.
- **General gallery paths** are also defined in `extdata.php`.

## **Galleries**
- **User gallery content** is listed by **scanning the filesystem**.
- **General gallery content** is listed by **querying the SQLite database** (optimized for performance and pagination).
- **Lazy loading** fetches more gallery images via AJAX calls to `ibrowser.php`, which also verifies the access token.

## **File Operations (`fileupload.php` & `upload.php`)**
- Forms in `fileupload.php` submit to `upload.php`.
- They include:
  - `access_token`
  - CSRF token (generated using a **Double Submit Cookie** pattern, stateless on the server).
- `upload.php` verifies both tokens, then securely processes actions (**upload, create folder, delete folder**) within validated gallery paths.
- **Uploads to the general gallery** also **update the SQLite index incrementally**.

## **Communication (iBrowser to TinyMCE)**
- When an image is selected in `ibrowser.php`, it uses:
  ```javascript
  window.parent.postMessage()
  ```
  to send the image data (**URL, alt, dimensions**) back to `plugin.js`.
- The `targetOrigin` for `postMessage` is set **dynamically** based on the `editorOrigin` parameter for security.
- `plugin.js` receives the message, processes the data, inserts the image into TinyMCE, and **closes the modal**.

---

# **Security Considerations**

### **IBROWSER_SHARED_SECRET_KEY**
- This is **critical** for HMAC token security.
- **Do not** use the **cookie-based method** in a production environment.
- Use a **strong, static, random key stored securely**.

### **CSRF Protection**
- The **Double Submit Cookie pattern** is used for forms.
- Ensure `upload.php` correctly validates the **CSRF token** from the form field against the cookie.

### **Path Traversal Prevention**
- All **path construction** and **file operations** are restricted within **configured base directories**.
- Uses `realpath()` and **sanitization** to **prevent traversal attacks**.

### **File Uploads**
- `upload.php` must **rigorously validate**:
  - **MIME type**
  - **File extension**
  - **File size**
  - **Sanitize filenames** before processing uploads.

### **postMessage Target Origin**
- `ibrowser.php` dynamically sets the `editorOrigin` for `postMessage` to **prevent data leaks** to unintended parent windows.

### **Protect `data/` Directory**
- The `.htaccess` file inside `data/` is **essential** to **deny direct web access**.

### **File & Directory Permissions**
- Ensure correct **read/write permissions**:
  - PHP needs **read access** for galleries.
  - **Write access** for upload/folder creation.
  - **Write access** to `data/` for SQLite.

### **Error Reporting**
- **Disable** `display_errors` in PHP for production.
- Rely on **error_log** for debugging.


# Complete example

```php
<?php
 // Codigo PHP para generar token de iBrowser

    //remember to edit exdata.php this lines have to be equal here and in exdata.php
    if (!defined('IBROWSER_SHARED_SECRET_KEY')) { // Definir solo si no está ya definida
        define('IBROWSER_SHARED_SECRET_KEY', "your long frase token here");
    }
    //REMEMBER TU EDIT the file exdata.php of the plugin with the same line of define('IBROWSER_SHARED_SECRET_KEY', "your long frase token here"); you entered before this line, in the 2 files have to be the same in this one and in exdata.php

    $USERVARIABLE = "default user"; //dont forget to change this, is for the user private gallery. 

    if (!function_exists('base64_url_encode_local')) {
        function base64_url_encode_local(string $data): string {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }
    }
    
    if (!function_exists('generate_ibrowser_access_token_local')) {
        function generate_ibrowser_access_token_local(string $userContext): string {
            $payload = json_encode([
                'ctx' => $userContext,
                'exp' => time() + 2700, 
                'nonce' => bin2hex(random_bytes(8))
            ]);
            $encodedPayload = base64_url_encode_local($payload);
            $signature = hash_hmac('sha256', $encodedPayload, IBROWSER_SHARED_SECRET_KEY, true);
            $encodedSignature = base64_url_encode_local($signature);
            return $encodedPayload . '.' . $encodedSignature;
        }
    }
    $user_context_identifier = $USERVARIABLE ?? 'default_user';
    $accessToken = generate_ibrowser_access_token_local($user_context_identifier);
    // Fin código PHP para iBrowser

    $tinymce_script_path_root_relative = "/incspt/jscripts/TinyMCE/7.9.1/tinymce.min.js"; 
    $tinymce_document_base_url_root_relative = "/incspt/jscripts/TinyMCE/7.9.1/";
    $tinymce_language_url_root_relative = "/incspt/jscripts/TinyMCE/7.9.1/langs/es_MX.js";
    
    echo '<script src="' . htmlspecialchars($tinymce_script_path_root_relative) . '"></script>';
    ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: 'textarea#contenido',
            license_key: 'gpl', 
            language: 'es_MX', 
            language_url: '<?php echo htmlspecialchars($tinymce_language_url_root_relative); ?>',
            document_base_url: '<?php echo htmlspecialchars($tinymce_document_base_url_root_relative); ?>', 
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 
                'table', 'help', 'wordcount', 'ibrowser'
            ],
            toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | ' +
                     'bullist numlist outdent indent | link image media ibrowser | ' + 
                     'forecolor backcolor emoticons | code fullscreen preview | help', 
            menubar: 'file edit view insert format tools table help',
            height: 750,
            image_advtab: true, 
            media_dimensions: false, 
            media_live_embeds: true, 
            paste_as_text: false, 
            convert_urls: false, 
            relative_urls: false, 
            remove_script_host: false, 
            forced_root_block: 'p',
            entity_encoding: 'raw', 
            branding: false,
            promotion: false,
            images_upload_url: '<?php echo $image_upload_url_for_tinymce; ?>',
            images_file_types: 'jpeg,jpg,jpe,jfi,jif,jfif,png,gif,bmp,webp,svg',
            automatic_uploads: true, 
            paste_data_images: true, 
            images_reuse_filename: false,
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            ibrowser_access_token: "<?php echo htmlspecialchars($accessToken, ENT_QUOTES, 'UTF-8'); ?>",
            ibrowser_context_identifier: "<?php echo htmlspecialchars($user_context_identifier, ENT_QUOTES, 'UTF-8'); ?>"
        });
    });
    </script>
```

---


iFmup version 0.1.3
=====

Repository for Dim Works Kernel
You can adjust it by deleting the cookie removal lines (remcurus) to make it work properly without Dim Works Kernel.
This is an image file uploader for the WYSIWYG TinyMCE editor. With this script, you can upload images directly to your web host and insert them into the text editor. It's easy to use and completely free.
Installation Instructions
To install, simply upload the top-level folder (ibrowser) into the plugin directory, then configure the script to call the text editor with the name ibrowser.
- This script requires the GD library for PHP. -
It was inspired by iBrowser, but iFmup does not require PHPThumbs, supports PHP 5, is much easier to use, and is not as heavy as iBrowser.

v0.1.1 Example for Installation
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,ibrowser,cleanup,help,code",


FAQs for install plugins & examples:

-http://www.tinymce.com/forum/viewtopic.php?id=3349

-http://www.tinymce.com/wiki.php/Configuration:plugins

More Details for Installation
- First, unzip the files and upload them to the plugins subfolder within your TinyMCE directory.
- When initializing TinyMCE in your HTML, make sure to specify the plugin name.
- Example:

ex: plugins : "ibrowser",

- Add the button to the theme’s toolbar list.
- Example:

ex: theme_advanced_buttons1 : "ibrowser",

- Add the button to the theme’s toolbar list.


///////////////////////////////////////////////////////////////////////////////////////

Complete Example:

## Complete Example for TinyMCE Plugin Integration

```html
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
    // General options
    mode: "textareas",
    language: "es",
    theme: "advanced",
    convert_urls: false,
    relative_urls: false,
    content_css: "style.css",
    plugins: "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,ibrowser",

    // Theme options
    theme_advanced_buttons1: "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,|,sub,sup",
    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,ibrowser,cleanup,help,code",
    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,spellchecker,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,
    spellchecker_languages: "English=en,+Spanish=es",

    // External lists for link/image/media/template dialogs
    template_external_list_url: "lists/template_list.js",
    external_link_list_url: "lists/link_list.js",
    external_image_list_url: "lists/image_list.js",
    media_external_list_url: "lists/media_list.js"
});
</script>

<textarea name="message" rows="20" cols="45" class="mceSimple" style="font-size: 1.1em;"></textarea>

# Notes about how I completed this project:

- This is a fork from the original project: https://github.com/VictoryCTO/LaravelSandbox
- I followed the instructions, and used Packager to develop the package/service in a local repository.
- I kept the packages dependencies within the package, so that once installed it works without a lot of configuration.
- I set it up to resize images with the Intervention package.
- The package creates its own database migrations, and eloquent model to use the database.
- It stores 3 versions of the uploaded image in a single database row for original, thumbnail, and small sizes.
- The package uploads files to AWS s3 storage.
- It pre-signs CloudFront CDN URLs to display the images from a distribution.
- After I completed the package development, I uploaded it to its own GitHub repository, and also on Packagist.
- Once it was on Packagist, I removed the local repository, and installed it like a normal composer package into the project.
- There is a live demo version running on an EC2 instance, using MySQL RDS database, with s3 and CloudFront distribution: http://imageuploadservice.jeremylbrammer.com/imageuploads
- The link to the package's GitHub repository: https://github.com/jeremybrammer/laravelimagetos3package
- The link to the package on Packagist: https://packagist.org/packages/jeremybrammer/laravelimagetos3package
- Below are instructions for installing this repository.
- Further instructions for using/installing the actual package can be found on its GitHub repo, or Packagist page.

# Installation Steps for this repository:

//Install the composer project:
composer install

//If installing in another project, require my package, but this is already in composer.json for this repo, so skip this if installing from here.
composer require jeremybrammer/laravelimagetos3package

//Publish the package's config files. It publishes a config file for a dependency.
php artisan vendor:publish --provider="jeremybrammer\laravelimagetos3package\laravelimagetos3packageServiceProvider"

//Migrate the database to get the new image uploads database table going.
php artisan migrate

//Change/Add the following lines in the .env file:
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_URL=
CLOUDFRONT_PRIVATE_KEY_PATH=keys/my_key.pem
CLOUDFRONT_KEY_PAIR_ID=

(Add your CloudFront key to /storage/keys/my_key.pem.  This should be .gitignored already).

Depending on your server configuration, increase nginx.conf and php.ini settings as needed to allow larger image uploads, and memory limits.

# Package Usage in Controllers:

//Include Laravel's Request class, and the following classes, models, and facades from my package.
use Illuminate\Http\Request;
use jeremybrammer\laravelimagetos3package\laravelimagetos3package;
use jeremybrammer\laravelimagetos3package\Models\ImageUpload;
use jeremybrammer\laravelimagetos3package\Facades\LaravelImageToS3PackageFacade;

//Gets all previously uploaded images and pre-signs the CloudFront URLs for the thumbnails.
LaravelImageToS3PackageFacade::getAllUploadedImages(); 

//Optionally override the image size settings in the upload service.
LaravelImageToS3PackageFacade::setWidthByImageType("thumbnail", 100);
LaravelImageToS3PackageFacade::setWidthByImageType("small", 200);

//Call the upload handler with the request, html image field name attribute, and folder in s3 to store them.
LaravelImageToS3PackageFacade::handUploadRequest($request, "image-upload-field", "victorycto/images");

//A controller example to view individual images that uses my eloquent model with route-model-binding:
public function view(ImageUpload $imageUpload, $imagetype){
    //Use route-model binding for the image object, and an image type to get the proper size.
    switch($imagetype){
        case "thumbnail": $url = $imageUpload->thumbnail_image_url; break;
        case "small": $url = $imageUpload->small_image_url; break;
        case "original": $url = $imageUpload->original_image_url; break;
        default: return; break;
    }
    // $imageURL = $this->imagetos3->preSignS3Url($imageUpload->original_image_url); //Sign s3 URL.
    $imageURL = LaravelImageToS3PackageFacade::preSignCloudFrontUrl($url); //Sign CloudFront URL.
    return view("imageuploads.view", ["imageURL" => $imageURL]);
}

Enjoy!  Below are original instructions for this project.

# Victory Laravel Sandbox Application

This application is designed for testing of Laravel Framework capabilities.  Please submit your changes as a Pull Request.

The framework is [Laravel 6.0.2](https://laravel.com) - this is the most recent LTS (Long Term Service) release of Laravel.  The framework has some additional packages installed which will be discussed below.

## Setup and Installation
_This is tested on MacOS, most *Nix architectures and Windows - there are some extra notes for node development in Windows_
- Install [Vagrant](https://vagrantup.com)
- Install [Virtualbox](https://www.virtualbox.org/wiki/Downloads)
- Check out this repository to ~/code/LaravelSandbox - if you choose a different location you just need to make some changes to `Homestead.yaml`
- `cp Homestead/Homestead.yaml.example Homestead/Homestead.yaml`
- `cp .env.example .env`
- `vagrant up`

Once the machine is up you can log in with `vagrant ssh` and change to the code 
directory `cd code` - this directory is a share from the host system.  Here you can run any cli items needed like artisan commands.

## Access to the site
The site will be available at the ip address in Homestead.yaml which currently defaults to `192.168.10.10`.  You can change that if it causes problems in your network.
If you'd like to use an easier address you can make an entry in `/etc/hosts` for something like `sandbox.test`


## Package Creation
A key part of this sandbox is to test the ability to create custom packages.  To that end, 
this sandbox comes with [Laravel-Packager](https://github.com/Jeroen-G/laravel-packager) - a 3rtd party
package designed to make package creation easy.  You can bootstrap a new package using this tool:
```
php artisan packager:new VENDOR NAME
```
For instance, try this:
```
php artisan packager:new victorycto mysandbox

```
Once complete there is a directory in your codebase under `/packages` built as `/packages/VENDOR/NAME` - so look for `/packages/victorycto/mysandbox`

_Note: Composer plans to enforce that all vendor and package names be lowercase in a future release, so please stick to that format_

*The repository will ignore the `/packages` directory, so work done here will not be loaded back to the repo*


## The Coding Test
We do not believe in standard coding tests at Victory, but we do want to see and understand your style of coding - we find the best way 
of doing that is to see you code a real world example.  You may use whatever tools you have available and build this 
in any way you see fit.  

Please fork this repository and do your work.  When done email us with the fork url.

### Build a Service
For this exercise you will build a simple service which can be utilized by the site from any controller.  This is
an image service, designed to ingest images and prepare them for use on the site.  Your service should:
- Accept a multipart form image upload
- Resize / Recompress the image to at least 3 sizes (think thumbnail, small and full).  You may change the image format and compression to best suite use on a website
- Store the image to S3 or GCP cloud storage and create a public url - ideally with a CDN frontend
- Save the image data to a table of your design in the local mysql database
- Make the image available to the frontend

You should be able to accomplish this within the free tier of either cloud service, and provide locations and directions in your code for
any needed setup.  If you need help or a paid account reach out and we will provide it.  

We're looking for best practices, good documentation and testing, and creativity.  

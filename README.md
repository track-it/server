# TrackIt Server 
[![Build Status](https://travis-ci.org/track-it/server.svg?branch=master)](https://travis-ci.org/track-it/server)

## Installation

### SSH-key

Before doing anything, make sure you have generated a SSH key. It should be located in `~/.ssh` and you should have a private key called `id_rsa` and a public key `id_rsa.pub`. If you don't have an SSH key, you can generate one with the `ssh-keygen` command.

### Homestead

You are recommended to install the Vagrant box called **Homestead** which includes everything required to run the project. To install Homestead, you first need both [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](https://www.vagrantup.com/downloads.html). There are installers for all major operative systems.

> **Note:** If you are using Windows, you may need to enable hardware virtualization (VT-x). It can usually be enabled via your BIOS. If you are using Hyper-V on a UEFI system you may additionally need to disable Hyper-V in order to access VT-x.

Once Vagrant is installed, you can run the `vagrant` command from your terminal. To install Homestead, clone the Homestead Git repository in your home directory:

```
cd ~

git clone https://github.com/laravel/homestead.git Homestead
```

Once the repo has been downloaded, cd into the `Homestead` directory and run `bash init.sh`. When you run the `init` script a new `.homestead` directory will be created in your home directory. Inside this directory you will have a file called `Homestead.yaml`. Replace this one with the [file included in this repository](https://github.com/track-it/server/blob/master/Homestead.yaml), but make sure you copy it and **leave the file in the repository untouched**. Only make the necessary changes once you have copied it.

When you have updated your `Homestead.yaml` file, run `vagrant provision` from the cloned `Homestead` directory.

### Hosts file
To finish the installation, edit your `hosts` file and include the following line inside it:

```
192.168.10.10      trackit.dev
```

On Mac OSX and Linux, your `hosts` file is located at `/etc/hosts`. On Windows, this file is located `C:\Windows\System32\drivers\etc\hosts`.

### Setup

Once Vagrant is setup, you can SSH into your virtual machine by running `vagrant ssh` from your `Homestead` directory. You can do everything related to the project from here as everything required is installed. If you want to, you can install php on your local machine to run, for example `artisan`, but that's not required and I won't include any walkthrough here.

First thing you want to do when you have setup your virtual machine is to cd into the trackit directory, located at `/home/vagrant/trackit`. Start by running the following command:

```
composer install
```

This will install all the dependencies for the Laravel project. When this is done, you should make a copy of the environment file:

```
cp .env.example .env
```

Again, **DO NOT CHANGE THE `.env.example` FILE**! After this, you should generate a random key for your application by running:

```
php artisan key:generate
```

`artisan` is a command that will let you generate a lot of boilerplates, run your migrations and other stuff. Documentation can be found at `https://laravel.com/docs/5.2/artisan`, but you can get a lot of information by just running `php artisan --help`.

## Instructions for commits

* Never commit directly on `master`. Always create a separate branch, with a name that is mapped to the issue you are working on. For example, when working on issue #39, then create a new branch with `git checkout -b [branch-name]` and name the branch `issue-39`.
* When you have commited and pushed changes on a development branch, make sure you have your code **reviewed** by someone in the team who have not written nor been helping you with the code. If possible, the code should also be black-box tested by someone without insight in the code and must be someone who did not review the code.
* When code review and tests have passed, you can merge your code into the master branch.
* As soon as your code has been merged to master and everything is working as intended, make sure you close the issue and delete the branch.

**NOTE:** To switch branches during development, use `git checkout [branch-name]`. Although, remember to either **stash** or **commit** your changes before it, or otherwise your changes will be moved to the branch you are switching to. To list your local branches, use `git branch`.

# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  config.vm.network :private_network, ip: "192.168.66.60"
  config.vm.network "forwarded_port", guest: 80, host: 8080, auto_correct: true
  config.vm.synced_folder '.', '/vagrant', nfs: true

  config.vm.provider :virtualbox do |vb|
    vb.name = "irestful-rodson"
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["modifyvm", :id, "--cpus", "8"]
    vb.customize ["modifyvm", :id, "--ostype", "Ubuntu_64"]
  end
  config.vm.provision "shell", inline: <<-shell

    export LANGUAGE=en_US.UTF-8;
    export LANG=en_US.UTF-8;
    export LC_ALL=en_US.UTF-8;
    locale-gen en_US.UTF-8;
    dpkg-reconfigure locales;

    sudo apt-get update -y;
    sudo DEBIAN_FRONTEND=noninteractive apt-get -y -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" dist-upgrade;

    sudo apt-get install python-software-properties -y --force-yes;
    sudo apt-get install software-properties-common -y --force-yes;

    sudo apt-get install curl -y --force-yes;
    sudo apt-get install git -y --force-yes;

    #update and upgrade
    sudo add-apt-repository ppa:ondrej/php5-5.6 -y;
    sudo apt-get update -y;
    sudo apt-get upgrade -y;
    sudo add-apt-repository ppa:ondrej/php5-5.6 -y;
    sudo apt-get install php5 -y --force-yes;
    sudo apt-get install php5-cli -y --force-yes;
    sudo apt-get install php5-fpm -y --force-yes;
    sudo apt-get install php5-xdebug -y --force-yes;
    sudo apt-get install php5-curl -y --force-yes;
    sudo apt-get purge apache2 -y --force-yes;
    sudo apt-get autoremove -y --force-yes;
    sudo apt-get update -y;
    sudo apt-get upgrade -y;

    #remove dependencies:
    sudo rm -R -f /vagrant/vendor;

    #delete/make the reports folder:
    sudo rm -R -f /vagrant/reports;
    mkdir /vagrant/reports;

    #download composer and install the dependencies:
    cd /vagrant; rm /vagrant/composer.phar;
    cd /vagrant; curl -sS https://getcomposer.org/installer | php;
    cd /vagrant; /vagrant/composer.phar install --prefer-source;

  shell
end
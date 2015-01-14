Vagrant.configure("2") do |config|
    config.dns.tld = "dev"
    config.vm.hostname = "coworking.a-metz.dev"
    config.dns.patterns = [/^.*coworking.a-metz.dev$/]

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.72"
    config.vm.hostname = "scotchbox"
    config.vm.synced_folder "_build/", "/var/www/public/", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder ".", "/home/vagrant/src/", :mount_options => ["dmode=777", "fmode=666"], create: true
end

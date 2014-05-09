
Name: app-joomla
Epoch: 1
Version: 1.0.0
Release: 1%{dist}
Summary: Joomla
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-webapp
Requires: app-system-database

%description
Joomla is an award-winning content management system (CMS), which enables you to build web sites and powerful online applications.

%package core
Summary: Joomla - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-webapp-core
Requires: app-system-database-core
Requires: webapp-joomla

%description core
Joomla is an award-winning content management system (CMS), which enables you to build web sites and powerful online applications.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/joomla
cp -r * %{buildroot}/usr/clearos/apps/joomla/

install -d -m 0755 %{buildroot}/var/clearos/joomla
install -d -m 0755 %{buildroot}/var/clearos/joomla/archive
install -d -m 0755 %{buildroot}/var/clearos/joomla/backup
install -d -m 0755 %{buildroot}/var/clearos/joomla/webroot
install -D -m 0644 packaging/webapp-joomla-flexshare.conf %{buildroot}/etc/clearos/flexshare.d/webapp-joomla.conf
install -D -m 0644 packaging/webapp-joomla-httpd.conf %{buildroot}/etc/httpd/conf.d/webapp-joomla.conf

%post
logger -p local6.notice -t installer 'app-joomla - installing'

%post core
logger -p local6.notice -t installer 'app-joomla-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/joomla/deploy/install ] && /usr/clearos/apps/joomla/deploy/install
fi

[ -x /usr/clearos/apps/joomla/deploy/upgrade ] && /usr/clearos/apps/joomla/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-joomla - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-joomla-core - uninstalling'
    [ -x /usr/clearos/apps/joomla/deploy/uninstall ] && /usr/clearos/apps/joomla/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/joomla/controllers
/usr/clearos/apps/joomla/htdocs
/usr/clearos/apps/joomla/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/joomla/packaging
%dir /usr/clearos/apps/joomla
%dir /var/clearos/joomla
%dir /var/clearos/joomla/archive
%dir /var/clearos/joomla/backup
%dir /var/clearos/joomla/webroot
/usr/clearos/apps/joomla/deploy
/usr/clearos/apps/joomla/language
/usr/clearos/apps/joomla/libraries
%config(noreplace) /etc/clearos/flexshare.d/webapp-joomla.conf
%config(noreplace) /etc/httpd/conf.d/webapp-joomla.conf

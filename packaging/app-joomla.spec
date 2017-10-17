
Name: app-joomla
Epoch: 1
Version: 1.0.1
Release: 1%{dist}
Summary: Joomla
License: GPLv3
Group: ClearOS/Apps
Packager: Xtreem Solution
Vendor: Xtreem Solution
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-certificate-manager
Requires: app-mariadb
Requires: app-php-engines
Requires: app-web-server >= 1:2.4.0
Requires: unzip
Requires: zip

%description
Joomla - a description goes here.

%package core
Summary: Joomla - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-certificate-manager-core
Requires: app-flexshare-core
Requires: app-mariadb-core
Requires: app-php-engines-core
Requires: app-web-server-core >= 1:2.4.5
Requires: app-webapp-core >= 1:2.4.0

%description core
Joomla - a description goes here.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/joomla
cp -r * %{buildroot}/usr/clearos/apps/joomla/

install -d -m 0775 %{buildroot}/var/clearos/joomla/backup
install -d -m 0775 %{buildroot}/var/clearos/joomla/versions

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
%exclude /usr/clearos/apps/joomla/unify.json
%dir /usr/clearos/apps/joomla
%dir %attr(0775,webconfig,webconfig) /var/clearos/joomla/backup
%dir %attr(0775,webconfig,webconfig) /var/clearos/joomla/versions
/usr/clearos/apps/joomla/deploy
/usr/clearos/apps/joomla/language
/usr/clearos/apps/joomla/libraries

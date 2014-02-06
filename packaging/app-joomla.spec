
Name: app-joomla
Epoch: 1
Version: 1.0.0
Release: 1%{dist}
Summary: Joomla Engine - Core
License: LGPLv3
Group: ClearOS/Libraries
Packager: Peter Baldwin
Vendor: Peter Baldwin
Source: app-joomla-%{version}.tar.gz
Buildarch: noarch

%description
Joomla Engine - a description goes here.

%package core
Summary: Joomla Engine - Core
Requires: app-base-core
Requires: app-web-server-core >= 1:1.4.40
Requires: webapp-joomla

%description core
Joomla Engine - a description goes here.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/joomla
cp -r * %{buildroot}/usr/clearos/apps/joomla/


%post core
logger -p local6.notice -t installer 'app-joomla-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/joomla/deploy/install ] && /usr/clearos/apps/joomla/deploy/install
fi

[ -x /usr/clearos/apps/joomla/deploy/upgrade ] && /usr/clearos/apps/joomla/deploy/upgrade

exit 0

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-joomla-core - uninstalling'
    [ -x /usr/clearos/apps/joomla/deploy/uninstall ] && /usr/clearos/apps/joomla/deploy/uninstall
fi

exit 0

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/joomla/packaging
%exclude /usr/clearos/apps/joomla/tests
%dir /usr/clearos/apps/joomla
/usr/clearos/apps/joomla/deploy
/usr/clearos/apps/joomla/language
/usr/clearos/apps/joomla/libraries

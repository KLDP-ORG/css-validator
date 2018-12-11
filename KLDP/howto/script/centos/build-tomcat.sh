#/bin/sh


# need java-utils

JAVAPACKAGES_DEBUG=1

if [ -f /usr/share/java-utils/java-functions ] ; then
	. /usr/share/java-utils/java-functions
	set_jvm
	set_javacmd
	set_jvm_dirs
fi

CATALINA_HOME="/usr/share/tomcat"
JVM_LIBDIR+=":${JAVA_HOME}/lib"

#CSS_HOME="${CATALINA_HOME}/webapps/css-validator"
CSS_HOME=$(pwd)

CLASSPATH+=":${CSS_HOME}"
CLASSPATH+=":${CSS_HOME}/WEB-INF/lib"

CLASSPATH_FILE=":$(find_jar tools.jar)"
[ $? -ne 0 ] && \
	printf "Error: %s not found\n" "tools.jar" && exit 1

CLASSPATH+=":${CLASSPATH_FILE}"

export CLASSPATH

rm -rf WEB-INF ./build ./lib *.war *.jar

#ant build
#[ $? -ne 0 ] && exit 1

TODAY=$(date +"%Y%m%d")
ant jar
ant war

pushd ../common/howto

	for ext in jar war
	do
		if [ -f "${CSS_HOME}/css-validator.${ext}" ]; then
		mv ${CSS_HOME}/css-validator.${ext} css-validator.${ext}-${TODAY}
		ln -sf css-validator.${ext}-${TODAY} css-validator.${ext}
		fi
	done

popd


# For css-validat.kldp.org

mkdir -p WEB-INF/classes

mv build/org WEB-INF/classes
mv ./lib WEB-INF/
install -m644 web.xml WEB-INF/

rm -rf build tmp

exit 0

#
# vim: filetype=sh
#

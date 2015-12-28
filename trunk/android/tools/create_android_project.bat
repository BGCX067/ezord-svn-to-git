rem project params
SET PROJECT_HOME=your_path
SET ANDROID_TARGET=sdk_id
SET PROJECT_NAME=EzOrder
SET PROJECT_ACTIVITY_NAME=EzOrderActivity
SET PACKAGE_NAME=com.ezorder

rem tools path
SET JAVA_HOME="D:\tools\jdk1.7.0\"
SET ANDROID_HOME="D:\tools\android\android-sdk\"

%ANDROID_HOME%\tools\android create project --target %ANDROID_TARGET% --name %PROJECT_NAME% --path %PROJECT_HOME% --activity %PROJECT_ACTIVITY_NAME% --package %PACKAGE_NAME%
# Bring in from Mono docker image
FROM mcr.microsoft.com/dotnet/core/sdk:2.2

RUN apt update && apt install -y mono-devel

# Copy over our .NET C# solution skeleton
COPY ./src /opt/executor
WORKDIR /opt/executor

# SDK is not public yet
# RUN if [ ! -d "sdk-csharp" ]; then wget -O sdk.tar.gz https://github.com/ProcessMaker/package-csharp/tarball/master; fi

RUN mv sdk-csharp ../
WORKDIR /opt/sdk-csharp
RUN chmod 755 build.sh && ./build.sh
WORKDIR /opt/executor
RUN mv ../sdk-csharp/bin . && rm -rf ../sdk-csharp

# Install required
#RUN nuget install Newtonsoft.Json -Version 12.0.1
#dotnet add package Newtonsoft.Json


# Bring in from Mono docker image
FROM mcr.microsoft.com/dotnet/core/sdk:2.2

# Copy over our .NET C# solution skeleton
COPY ./src /opt/executor

# Set working directory to our /opt/executor location
WORKDIR /opt/executor

# Install required
#RUN nuget install Newtonsoft.Json -Version 12.0.1
#dotnet add package Newtonsoft.Json


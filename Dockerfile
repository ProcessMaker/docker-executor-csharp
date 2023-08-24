FROM mcr.microsoft.com/dotnet/core/sdk:2.2
RUN apt update

# Copy over our .NET C# solution skeleton
COPY ./src /opt/executor

RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list

# Install mono, needed for building the SDK
RUN apt install -y apt-transport-https dirmngr gnupg ca-certificates
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 3FA7E0328081BFF6A14DA29AA6A19B38D3D831EF
RUN echo "deb https://download.mono-project.com/repo/debian stable-stretch main" | tee /etc/apt/sources.list.d/mono-official-stable.list
RUN apt update
RUN apt install -y mono-devel

WORKDIR /opt/executor

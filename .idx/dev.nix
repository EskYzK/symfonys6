# To learn more about how to use Nix to configure your environment
# see: https://developers.google.com/idx/guides/customize-idx-env
{ pkgs, ... }: {
  # Which nixpkgs channel to use.
  channel = "unstable"; # or "stable-23.11"
  # Use https://search.nixos.org/packages to find packages
  packages = [
    pkgs.php84
    pkgs.symfony-cli
    pkgs.php84Packages.composer
  ];

  services.mysql = {
    enable = true;
    package = pkgs.mariadb;
    # package = pkgs.mysql80; # For MySQL 8.0
  };

  # Sets environment variables in the workspace
  env = {};
  idx = {
    # Search for the extensions you want on https://open-vsx.org/ and use "publisher.id"
    extensions = [
      "rangav.vscode-thunder-client"
    ];
    workspace = {
      onCreate = {
        # Open editors for the following files by default, if they exist:
        default.openFiles = ["public/index.php"];
      };
      # Runs when a workspace is (re)started
      onStart= {
        run-server = "symfony server:start";
      };
    };
  };
}
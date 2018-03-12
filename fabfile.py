from fabric.api import local

def prepare_deploy():
	local("git add .")
	local("git commit -m 'test'")
	local("git push -u origin master")

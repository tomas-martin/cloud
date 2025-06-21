pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'tomasmartin7/veterinaria-backend:latest'
        DOCKER_CREDENTIALS_ID = 'docker-hub'
    }

    stages {
        stage('Clonar repo') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker') {
            steps {
                script {
                    docker.build(DOCKER_IMAGE)
                }
            }
        }

        stage('Push a Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: DOCKER_CREDENTIALS_ID,
                                                 usernameVariable: 'DOCKER_USER',
                                                 passwordVariable: 'DOCKER_PASS')]) {
                    sh """
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push $DOCKER_IMAGE
                    """
                }
            }
        }
    }
}

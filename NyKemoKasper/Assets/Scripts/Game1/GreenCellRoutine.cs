﻿using System.Collections;
using UnityEngine;

public class GreenCellRoutine : MonoBehaviour
{
    private Rigidbody2D rb;

    private GameManager gameManager;

    private CellSpawner spawnerScript;

    private Animator anim;

    private bool isSpawningProcessFinished, isReplicating;

    private float chancesForReplication, replicationTime, speed;

    public bool isInstantiatedInReplication = false;



    void Start()
    {
        rb = GetComponent<Rigidbody2D>();
        gameManager = FindObjectOfType<GameManager>();
        speed = gameManager.greenCellsSpeed;
        anim = GetComponent<Animator>();

        chancesForReplication = gameManager.chancesForReplication;
        replicationTime = gameManager.replicationTime;

        isReplicating = false;


        if (!isInstantiatedInReplication)
        {
            isSpawningProcessFinished = false;
            StartCoroutine("StartCellRoutine");
            anim.speed = 1;
        }
        else
        {
            isSpawningProcessFinished = true;
            anim.Play("GreenCellWalk");
            anim.speed = speed / 3;
        }
    }

    void Update()
    {
        if (isSpawningProcessFinished)
        { 
            if (!isReplicating)
            {
                if (transform.position.y > -500)
                {
                    float randomVal = Random.Range(0.0f, 1000.0f);

                    if (randomVal < GetChancesForDuplicating() * chancesForReplication)
                    {
                        StartCoroutine("StartReplicationRoutine");
                        anim.speed = 1;
                    }
                    else
                    {
                        rb.velocity = new Vector3(0, -speed * 10, 0);
                    }
                }
                else
                {
                    rb.velocity = new Vector3(0, -speed * 10, 0);
                }
            }
        }

        if(transform.position.y < -630)
        {
            Destroy(this.gameObject);
            gameManager.DecreaseStats();
        }
    }

    public void ZeroSpeed()
    {
        if (rb != null)
        {
            rb.constraints = RigidbodyConstraints2D.FreezePositionY;
        }
    }

    public void RestoreSpeed()
    {
        rb.constraints = RigidbodyConstraints2D.FreezePositionX;
        rb.constraints = RigidbodyConstraints2D.FreezeRotation;
    }

    private float GetChancesForDuplicating()
    {
        float ratio = gameManager.numberOfGreenCells / gameManager.maxNumberOfGreenCells;
        float chances = 0;

        if(ratio < 0.1)
        {
            chances = 0.1f;
        }
        else if (ratio < 0.3)
        {
            chances = 0.08f;
        }
        else if (ratio < 0.5)
        {
            chances = 0.06f;
        }
        else if (ratio < 0.7)
        {
            chances = 0.04f;
        }
        else
        {
            chances = 0.02f;
        }

        return chances;
    }

    IEnumerator StartCellRoutine()
    {
        yield return new WaitForSeconds(1.04f);
        isSpawningProcessFinished = true;
        anim.Play("GreenCellWalk");
        anim.speed = speed / 3;
    }

    IEnumerator StartReplicationRoutine()
    {
        ZeroSpeed();
        anim.Play("GreenCellIdle");
        anim.speed = 1;
        isReplicating = true;
        yield return new WaitForSeconds(replicationTime);

        anim.Play("GreenCellReplicate");
        yield return new WaitForSeconds(0.4f);

        gameManager.ReplicateCell(transform);
        rb.constraints = RigidbodyConstraints2D.None;
        transform.Translate(new Vector3(-30, 0, 0));
        RestoreSpeed();
        isReplicating = false;
        anim.Play("GreenCellWalk");
        anim.speed = speed / 3;
    }
}
